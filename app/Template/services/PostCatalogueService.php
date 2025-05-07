<?php

namespace App\Services;

use App\Models\FirstModelCatalogue;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\FirstModelCatalogueServiceInterface;
use App\Repositories\Interfaces\FirstModelCatalogueRepositoryInterface as FirstModelCatalogueRepository;
use App\Repositories\Interfaces\LanguageRepositoryInterface as languageRepository;
use App\Repositories\RouterRepository as RouterRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Services\BaseService;
use Illuminate\Support\Str;
use App\Classes\Nestedsetbie;





/**
 * Class UserService
 * @package App\Services
 */
class FirstModelCatalogueService  extends BaseService implements FirstModelCatalogueServiceInterface
{
    protected $firstModelCatalogueRepository;
    protected $languageRepository;
    // protected $nestedSet;
    protected $routerRepository;
    protected $controllerName = 'FirstModelCatalogueController';
    public function __construct(FirstModelCatalogueRepository $firstModelCatalogueRepository, RouterRepository $routerRepository, languageRepository $languageRepository)
    {
        $this->firstModelCatalogueRepository = $firstModelCatalogueRepository;
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedSet = new Nestedsetbie(
            [
                'table' => 'firstModel_catalogues',
                'foreignkey' => 'firstModel_catalogue_id',
                'language_id' => $this->currentLanguage(),
            ]
        );
    }
    public function select()
    {
        return ['firstModel_catalogues.id', 'firstModel_catalogues.publish', 'firstModel_catalogues.image', 'firstModel_catalogues.level', 'firstModel_catalogues.order', 'tb2.name', 'tb2.canonical'];
    }
    public function paginate($request)
    {

        $locale = App::getLocale();
        $language = $this->languageRepository->findByCondition([['canonical', '=', $locale]]);
        $languageId = $language->id;
        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $perpage = $request->integer('perpage');
        $firstModelCatalogues = $this->firstModelCatalogueRepository->pagination(
            $select,
            $condition,
            $perpage,
            ['path' => 'firstModel/catalogue/index'],
            ['firstModel_catalogues.lft' => 'asc'],
            [
                ['firstModel_catalogue_language as tb2', 'tb2.firstModel_catalogue_id', '=', 'firstModel_catalogues.id']
            ],
            [],
            [
                ['tb2.language_id', '=', $languageId]
            ]
        );
        return $firstModelCatalogues;
    }

    public function create($request)
    {
        // die();
        DB::beginTransaction();
        try {

            $firstModelCatalogue = $this->createFirstModelCatalogue($request);

            if ($firstModelCatalogue->id > 0) {

                $payloadLanguage = $this->updateLanguageForCatalogue($firstModelCatalogue, $request);
                // die();

                // dd($payloadLanguage);
                $this->createRouter($firstModelCatalogue, $request, $this->controllerName);
                // die();
                // $this->routerRepository->create($routerPayload);
                $this->nestedSet();
            }
            DB::commit();

            return $firstModelCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during firstModelCatalogue creation: ' . $e->getMessage());
            throw new \Exception('Error creating the firstModelCatalogue: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        // echo 1;
        // die();
        DB::beginTransaction();
        try {


            $firstModelCatalogue = $this->firstModelCatalogueRepository->findById($id);
            $flag = $this->updateFirstModelCatalogue($id, $request);
            if ($flag) {
                $this->updateLanguageForCatalogue($firstModelCatalogue, $request);
            }

            $this->updateRouter($firstModelCatalogue, $request, $this->controllerName);



            $this->nestedSet->Get();
            $this->nestedSet->Recursive(0, $this->nestedSet->Set());
            $this->nestedSet->Action();
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
            $user = $this->firstModelCatalogueRepository->delete($id);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatus($firstModel = [])
    {
        DB::beginTransaction();
        try {
            // dd($firstModel);
            $payload[$firstModel['field']] = (($firstModel['value'] == '1') ? '0' : '1');
            $this->firstModelCatalogueRepository->update($firstModel['modelId'], $payload);
            // dd($firstModel);
            $this->changeUserStatus($firstModel, $payload[$firstModel['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($firstModel)
    {
        DB::beginTransaction();
        try {
            $payload[$firstModel['field']] = $firstModel['value'];
            $flag = $this->firstModelCatalogueRepository->updateByWhereIn('id', $firstModel['id'], $payload);
            $this->changeUserStatus($firstModel, $payload[$firstModel['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function changeUserStatus($firstModel,  $value)
    {
        // dd($firstModel);
        DB::beginTransaction();
        try {
            $array = [];
            if (isset($firstModel['modelId'])) {
                $array[] = $firstModel['modelId'];
            } else {
                $array = $firstModel['id'];
            }
            $payload[$firstModel['field']] = $value;
            $this->firstModelCatalogueRepository->updateByWhereIn('firstModel_catalogues.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }

    private function updateFirstModelCatalogue($id, $request)
    {
        // $firstModelCatalogue = $this->firstModelCatalogueRepository->findById($id);
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = json_encode($payload['album']);
        $flag = $this->firstModelCatalogueRepository->update($id, $payload);
        return $flag;
    }

    private function createFirstModelCatalogue($request)
    {
        $payload = $request->only($this->payload());
        // dd($payload);
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload['album']);
        // dd($payload['album']);
        // dd($payload);
        $firstModelCatalogue = $this->firstModelCatalogueRepository->create($payload);
        return $firstModelCatalogue;
    }

    private function updateLanguageForCatalogue($firstModelCatalogue, $request)
    {

        $payloadLanguage = $this->formatLanguagePayload($firstModelCatalogue, $request);
        // dd($payloadLanguage);
        // dd($payloadLanguage['language_id']);
        $firstModelCatalogue->languages()->detach([$payloadLanguage['language_id'], $firstModelCatalogue->id]);
        $translate = $this->firstModelCatalogueRepository->createPivot($firstModelCatalogue, $payloadLanguage, 'languages');
        // dd($translate);
        return $translate;
    }

    private function formatLanguagePayload($firstModelCatalogue, $request)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->currentLanguage();
        $payloadLanguage['firstModel_catalogue_id'] = $firstModelCatalogue->id;
        $payloadLanguage['canonical'] =  Str::slug($payloadLanguage['canonical']);
        // dd($payloadLanguage);
        return $payloadLanguage;
    }





    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image', 'album'];
    }
    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}