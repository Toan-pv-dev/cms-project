<?php

namespace App\Services;

use App\Models\AttributeCatalogue;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\AttributeCatalogueServiceInterface;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
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
class AttributeCatalogueService  extends BaseService implements AttributeCatalogueServiceInterface
{
    protected $attributeCatalogueRepository;
    protected $languageRepository;
    // protected $nestedSet;
    protected $routerRepository;
    protected $controllerName = 'AttributeCatalogueController';
    public function __construct(AttributeCatalogueRepository $attributeCatalogueRepository, RouterRepository $routerRepository, languageRepository $languageRepository)
    {
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedSet = new Nestedsetbie(
            [
                'table' => 'attribute_catalogues',
                'foreignkey' => 'attribute_catalogue_id',
                'language_id' => $this->currentLanguage(),
            ]
        );
    }
    public function select()
    {
        return ['attribute_catalogues.id', 'attribute_catalogues.publish', 'attribute_catalogues.image', 'attribute_catalogues.level', 'attribute_catalogues.order', 'tb2.name', 'tb2.canonical'];
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
        $attributeCatalogues = $this->attributeCatalogueRepository->pagination(
            $select,
            $condition,
            $perpage,
            ['path' => 'attribute/catalogue/index'],
            ['attribute_catalogues.lft' => 'asc'],
            [
                ['attribute_catalogue_language as tb2', 'tb2.attribute_catalogue_id', '=', 'attribute_catalogues.id']
            ],
            [],
            [
                ['tb2.language_id', '=', $languageId]
            ]
        );
        return $attributeCatalogues;
    }

    public function create($request)
    {
        // die();
        DB::beginTransaction();
        try {

            $attributeCatalogue = $this->createAttributeCatalogue($request);
            if ($attributeCatalogue->id > 0) {

                $payloadLanguage = $this->updateLanguageForCatalogue($attributeCatalogue, $request);
                // die();

                // dd($payloadLanguage);
                $this->createRouter($attributeCatalogue, $request, $this->controllerName, $this->currentLanguage());
                // dd($this->currentLanguage());
                // die();
                // $this->routerRepository->create($routerPayload);
                $this->nestedSet();
            }
            DB::commit();

            return $attributeCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during attributeCatalogue creation: ' . $e->getMessage());
            throw new \Exception('Error creating the attributeCatalogue: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        // echo 1;
        // die();
        DB::beginTransaction();
        try {


            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            $flag = $this->updateAttributeCatalogue($id, $request);
            if ($flag) {
                $this->updateLanguageForCatalogue($attributeCatalogue, $request);
            }

            $this->updateRouter($attributeCatalogue, $request, $this->controllerName, $this->currentLanguage());



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
            $user = $this->attributeCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByWhere([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controller\Frontend\\' . $this->controllerName],
            ]);
            DB::commit();
            return $user;
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
            $this->attributeCatalogueRepository->update($attribute['modelId'], $payload);
            // dd($attribute);
            $this->changeUserStatus($attribute, $payload[$attribute['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($attribute)
    {
        DB::beginTransaction();
        try {
            $payload[$attribute['field']] = $attribute['value'];
            $flag = $this->attributeCatalogueRepository->updateByWhereIn('id', $attribute['id'], $payload);
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
            $this->attributeCatalogueRepository->updateByWhereIn('attribute_catalogues.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }

    private function updateAttributeCatalogue($id, $request)
    {
        // $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = json_encode($payload['album']);
        $flag = $this->attributeCatalogueRepository->update($id, $payload);
        return $flag;
    }

    private function createAttributeCatalogue($request)
    {
        $payload = $request->only($this->payload());
        // dd($payload);
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload['album']);
        // dd($payload['album']);
        // dd($payload);
        $attributeCatalogue = $this->attributeCatalogueRepository->create($payload);
        return $attributeCatalogue;
    }

    private function updateLanguageForCatalogue($attributeCatalogue, $request)
    {

        $payloadLanguage = $this->formatLanguagePayload($attributeCatalogue, $request);
        // dd($payloadLanguage);
        // dd($payloadLanguage['language_id']);
        $attributeCatalogue->languages()->detach([$payloadLanguage['language_id'], $attributeCatalogue->id]);
        $translate = $this->attributeCatalogueRepository->createPivot($attributeCatalogue, $payloadLanguage, 'languages');
        // dd($translate);
        return $translate;
    }

    private function formatLanguagePayload($attributeCatalogue, $request)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->currentLanguage();
        $payloadLanguage['attribute_catalogue_id'] = $attributeCatalogue->id;
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
