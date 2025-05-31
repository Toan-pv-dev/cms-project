<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\AttributeServiceInterface;
use App\Repositories\AttributeRepository as AttributeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\BaseService;
use App\Repositories\RouterRepository as RouterRepository;

use Illuminate\Support\Str;


/**
 * Class UserService
 * @package App\Services
 */
class AttributeService  extends BaseService implements AttributeServiceInterface
{
    protected $attributeRepository;
    protected $nestedSet;
    protected $controllerName = 'AttributeController';

    public function __construct(AttributeRepository $attributeRepository, RouterRepository $routerRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->routerRepository = $routerRepository;
    }
    public function select()
    {
        return ['attributes.id', 'attributes.publish', 'attributes.image', 'attributes.order', 'tb2.name'];
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
            ['attribute_language as tb2', 'tb2.attribute_id', '=', 'attributes.id'],
            ['attribute_catalogue_attribute as tb3', 'attributes.id', '=', 'tb3.attribute_id']
        ];





        return $this->attributeRepository->pagination(
            $select,         // Columns to select
            $condition,      // Conditions
            $perPage,        // Items per page
            ['path' => 'attribute/index', 'groupBy' => $this->select()], // Extended options
            ['attributes.created_at' => 'DESC'], // Order by
            $joins,
            ['attribute_catalogues'],
            $this->whereRaw($request),
        );
    }


    public function create($request)
    {
        DB::beginTransaction();
        try {
            $attribute = $this->createAttribute($request);

            if ($attribute->id > 0) {
                $this->updateLanguageForAttribute($attribute, $request);
                $this->updateCatalogueForAttribute($attribute, $request);
                $this->createRouter($attribute, $request, $this->controllerName, $this->currentLanguage());
            }
            DB::commit();
            return $attribute;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during attribute creation: ' . $e->getMessage());
            throw new \Exception('Error creating the attribute: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        DB::beginTransaction();
        try {

            $attribute = $this->attributeRepository->findById($id);

            // $flag = $this->attributeRepository->update($id, $payload);
            if ($this->updateAttribute($attribute->id, $request)) {


                $this->updateLanguageForAttribute($attribute, $request);
                $this->updateCatalogueForAttribute($attribute, $request);
                $this->updateRouter($attribute, $request, $this->controllerName, $this->currentLanguage());
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

            $this->attributeRepository->forceDelete($id);
            $this->routerRepository->forceDeleteByWhere([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controller\Frontend\\' . $this->controllerName],
            ]);



            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatus($attribute = [])
    {
        DB::beginTransaction();
        try {
            // dd($attribute);
            $payload[$attribute['field']] = (($attribute['value'] == '1') ? '0' : '1');
            $this->attributeRepository->update($attribute['modelId'], $payload);
            $this->changeUserStatus($attribute, $payload[$attribute['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($attribute = [])
    {
        DB::beginTransaction();
        try {
            $payload[$attribute['field']] = $attribute['value'];
            $flag = $this->attributeRepository->updateByWhereIn('id', $attribute['id'], $payload);
            $this->changeUserStatus($attribute, $payload[$attribute['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function changeUserStatus($attribute,  $value)
    {
        // dd($attribute);
        DB::beginTransaction();
        try {
            $array = [];
            if (isset($attribute['modelId'])) {
                $array[] = $attribute['modelId'];
            } else {
                $array = $attribute['id'];
            }
            $payload[$attribute['field']] = $value;
            $this->attributeRepository->updateByWhereIn('attributes.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function createAttribute($request)
    {

        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload);
        $attribute = $this->attributeRepository->create($payload);
        // dd($attribute);
        return $attribute;
    }

    private function updateAttribute($id, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->attributeRepository->update($id, $payload);
    }


    private function updateLanguageForAttribute($attribute, $request)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $attribute->id);
        $attribute->languages()->detach([$this->currentLanguage(), $attribute->id]);
        return  $this->attributeRepository->createPivot($attribute, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $attributeId)
    {
        $payload['canonical'] =  Str::slug($payload['canonical']);
        $payload['language_id'] = $this->currentLanguage();
        $payload['attribute_id'] = $attributeId;
        return $payload;
    }
    private function updateCatalogueForAttribute($attribute, $request)
    {
        $res = $attribute->attribute_catalogues()->sync($this->catalogue($request));
    }

    private function catalogue($request)
    {
        // Lấy dữ liệu từ request, đảm bảo nó là một mảng
        $attributeCatalogueIds = $request->input('attribute_catalogue', []);

        // Ép kiểu thành mảng (nếu là null hoặc chuỗi)
        $attributeCatalogueIds = is_array($attributeCatalogueIds) ? $attributeCatalogueIds : [$attributeCatalogueIds];

        // Loại bỏ giá trị trống và trùng lặp
        return array_filter(array_unique($attributeCatalogueIds));
    }

    private function whereRaw($request)
    {
        $rawCondition = [];
        // dd($request->integer('attribute_catalogue_id'));
        if ($request->integer('attribute_catalogue_id') > 0) {
            $rawCondition['whereRaw'] =
                [
                    [
                        'tb3.attribute_catalogue_id IN (SELECT id FROM attribute_catalogues
                    WHERE lft >= (SELECT lft FROM attribute_catalogues AS pc WHERE pc.id = ?)
                    AND rgt <= (SELECT rgt FROM attribute_catalogues AS pc WHERE pc.id = ?)
                )',
                        [$request->integer('attribute_catalogue_id'), $request->integer('attribute_catalogue_id')]
                    ]
                ];
        }
        // dd($rawCondition);
        return $rawCondition;
        // dd($rawCondition);
    }

    private function payload()
    {
        return ['follow', 'publish', 'image', 'album', 'attribute_catalogue_id'];
    }
    private function payloadLanguage()
    {
        return ['name', 'attribute_id', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
