<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\ModelNameServiceInterface;
use App\Repositories\ModelNameRepository as ModelNameRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\BaseService;
use App\Repositories\RouterRepository as RouterRepository;

use Illuminate\Support\Str;


/**
 * Class UserService
 * @package App\Services
 */
class ModelNameService  extends BaseService implements ModelNameServiceInterface
{
    protected $modelNameRepository;
    protected $nestedSet;
    protected $controllerName = 'ModelNameController';

    public function __construct(ModelNameRepository $modelNameRepository, RouterRepository $routerRepository)
    {
        $this->modelNameRepository = $modelNameRepository;
        $this->routerRepository = $routerRepository;
    }
    public function select()
    {
        return ['modelNames.id', 'modelNames.publish', 'modelNames.image', 'modelNames.order', 'tb2.name'];
    }
    public function paginate($request)
    {
        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $condition['where'] = [
            ['tb2.language_id', '=', $this->currentLanguage()],
        ];

        $perPage = $request->integer('perpage');
        $joins = [
            ['modelName_language as tb2', 'tb2.modelName_id', '=', 'id'],
            ['modelName_catalogue_modelName as tb3', 'modelNames.id', '=', 'tb3.modelName_id']
        ];





        return $this->modelNameRepository->pagination(
            $select,         // Columns to select
            $condition,      // Conditions
            $perPage,        // Items per page
            ['path' => 'modelName/index', 'groupBy' => $this->select()], // Extended options
            ['modelNames.id' => 'DESC'], // Order by
            $joins,
            ['modelName_catalogues'],
            $this->whereRaw($request),
        );
    }


    public function create($request)
    {
        DB::beginTransaction();
        try {
            $modelName = $this->createModelName($request);

            if ($modelName->id > 0) {
                $this->updateLanguageForModelName($modelName, $request);
                $this->updateCatalogueForModelName($modelName, $request);
                $this->createRouter($modelName, $request, $this->controllerName);
            }
            DB::commit();
            return $modelName;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during modelName creation: ' . $e->getMessage());
            throw new \Exception('Error creating the modelName: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        DB::beginTransaction();
        try {

            $modelName = $this->modelNameRepository->findById($id);

            // $flag = $this->modelNameRepository->update($id, $payload);
            if ($this->updateModelName($modelName->id, $request)) {


                $this->updateLanguageForModelName($modelName, $request);
                $this->updateCatalogueForModelName($modelName, $request);
                $this->updateRouter($modelName, $request, $this->controllerName);
            }
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->modelNameRepository->delete($id);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatus($modelName = [])
    {
        DB::beginTransaction();
        try {
            // dd($modelName);
            $payload[$modelName['field']] = (($modelName['value'] == '1') ? '0' : '1');
            $this->modelNameRepository->update($modelName['modelId'], $payload);
            $this->changeUserStatus($modelName, $payload[$modelName['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($modelName = [])
    {
        DB::beginTransaction();
        try {
            $payload[$modelName['field']] = $modelName['value'];
            $flag = $this->modelNameRepository->updateByWhereIn('id', $modelName['id'], $payload);
            $this->changeUserStatus($modelName, $payload[$modelName['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function changeUserStatus($modelName,  $value)
    {
        // dd($modelName);
        DB::beginTransaction();
        try {
            $array = [];
            if (isset($modelName['modelId'])) {
                $array[] = $modelName['modelId'];
            } else {
                $array = $modelName['id'];
            }
            $payload[$modelName['field']] = $value;
            $this->modelNameRepository->updateByWhereIn('modelNames.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function createModelName($request)
    {

        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload);
        $modelName = $this->modelNameRepository->create($payload);
        // dd($modelName);
        return $modelName;
    }

    private function updateModelName($id, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload['album']);
        // dd($id);
        return $this->modelNameRepository->update($id, $payload);
    }


    private function updateLanguageForModelName($modelName, $request)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $modelName->id);
        $modelName->languages()->detach([$this->currentLanguage(), $modelName->id]);
        return  $this->modelNameRepository->createPivot($modelName, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $modelNameId)
    {
        $payload['canonical'] =  Str::slug($payload['canonical']);
        $payload['language_id'] = $this->currentLanguage();
        $payload['modelName_id'] = $modelNameId;
        return $payload;
    }
    private function updateCatalogueForModelName($modelName, $request)
    {
        $modelName->modelName_catalogues()->sync($this->catalogue($request));
    }

    private function catalogue($request)
    {
        // Lấy dữ liệu từ request, đảm bảo nó là một mảng
        $modelNameCatalogueIds = $request->input('modelName_catalogue', []);

        // Ép kiểu thành mảng (nếu là null hoặc chuỗi)
        $modelNameCatalogueIds = is_array($modelNameCatalogueIds) ? $modelNameCatalogueIds : [$modelNameCatalogueIds];

        // Loại bỏ giá trị trống và trùng lặp
        return array_filter(array_unique($modelNameCatalogueIds));
    }

    private function whereRaw($request)
    {
        $rawCondition = [];
        // dd($request->integer('modelName_catalogue_id'));
        if ($request->integer('modelName_catalogue_id') > 0) {
            $rawCondition['whereRaw'] =
                [
                    [
                        'tb3.modelName_catalogue_id IN (SELECT id FROM modelName_catalogues
                    WHERE lft >= (SELECT lft FROM modelName_catalogues AS pc WHERE pc.id = ?)
                    AND rgt <= (SELECT rgt FROM modelName_catalogues AS pc WHERE pc.id = ?)
                )',
                        [$request->integer('modelName_catalogue_id'), $request->integer('modelName_catalogue_id')]
                    ]
                ];
        }
        // dd($rawCondition);
        return $rawCondition;
        // dd($rawCondition);
    }

    private function payload()
    {
        return ['follow', 'publish', 'image', 'album', 'modelName_catalogue_id'];
    }
    private function payloadLanguage()
    {
        return ['name', 'modelName_id', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
