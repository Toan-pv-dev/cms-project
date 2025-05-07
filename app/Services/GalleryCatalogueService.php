<?php

namespace App\Services;

use App\Models\GalleryCatalogue;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\GalleryCatalogueServiceInterface;
use App\Repositories\Interfaces\GalleryCatalogueRepositoryInterface as GalleryCatalogueRepository;
use App\Repositories\Interfaces\LanguageRepositoryInterface as languageRepository;
use App\Repositories\RouterRepository as RouterRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Services\BaseService;
use Illuminate\Support\Str;
use App\Classes\Nestedsetbie;


// ModuleName
// moduleName
// gallery_catalogues
// module_name
// modulePath
// headModule



/**
 * Class UserService
 * @package App\Services
 */
class GalleryCatalogueService  extends BaseService implements GalleryCatalogueServiceInterface
{
    protected $galleryCatalogueRepository;
    protected $languageRepository;
    // protected $nestedSet;
    protected $routerRepository;
    protected $controllerName = 'GalleryCatalogueController';
    public function __construct(GalleryCatalogueRepository $galleryCatalogueRepository, RouterRepository $routerRepository, languageRepository $languageRepository)
    {
        $this->galleryCatalogueRepository = $galleryCatalogueRepository;
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedSet = new Nestedsetbie(
            [
                'table' => 'gallery_catalogues',
                'foreignkey' => 'gallery_catalogue_id',
                'language_id' => $this->currentLanguage(),
            ]
        );
    }
    public function select()
    {
        return ['gallery_catalogues.id', 'gallery_catalogues.publish', 'gallery_catalogues.image', 'gallery_catalogues.level', 'gallery_catalogues.order', 'tb2.name', 'tb2.canonical'];
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
        $galleryCatalogues = $this->galleryCatalogueRepository->pagination(
            $select,
            $condition,
            $perpage,
            ['path' => 'gallery/catalogue/index'],
            ['gallery_catalogues.lft' => 'asc'],
            [
                ['gallery_catalogue_language as tb2', 'tb2.gallery_catalogue_id', '=', 'gallery_catalogues.id']
            ],
            [],
            [
                ['tb2.language_id', '=', $languageId]
            ]
        );
        return $galleryCatalogues;
    }

    public function create($request)
    {
        // die();
        DB::beginTransaction();
        try {

            $galleryCatalogue = $this->createGalleryCatalogue($request);

            if ($galleryCatalogue->id > 0) {

                $payloadLanguage = $this->updateLanguageForCatalogue($galleryCatalogue, $request);
                // die();

                // dd($payloadLanguage);
                $this->createRouter($galleryCatalogue, $request, $this->controllerName);
                // die();
                // $this->routerRepository->create($routerPayload);
                $this->nestedSet();
            }
            DB::commit();

            return $galleryCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during galleryCatalogue creation: ' . $e->getMessage());
            throw new \Exception('Error creating the galleryCatalogue: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        // echo 1;
        // die();
        DB::beginTransaction();
        try {


            $galleryCatalogue = $this->galleryCatalogueRepository->findById($id);
            $flag = $this->updateGalleryCatalogue($id, $request);
            if ($flag) {
                $this->updateLanguageForCatalogue($galleryCatalogue, $request);
            }

            $this->updateRouter($galleryCatalogue, $request, $this->controllerName);



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
            $user = $this->galleryCatalogueRepository->delete($id);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatus($gallery = [])
    {
        DB::beginTransaction();
        try {
            // dd($gallery);
            $payload[$gallery['field']] = (($gallery['value'] == '1') ? '0' : '1');
            $this->galleryCatalogueRepository->update($gallery['modelId'], $payload);
            // dd($gallery);
            $this->changeUserStatus($gallery, $payload[$gallery['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($gallery)
    {
        DB::beginTransaction();
        try {
            $payload[$gallery['field']] = $gallery['value'];
            $flag = $this->galleryCatalogueRepository->updateByWhereIn('id', $gallery['id'], $payload);
            $this->changeUserStatus($gallery, $payload[$gallery['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function changeUserStatus($gallery,  $value)
    {
        // dd($gallery);
        DB::beginTransaction();
        try {
            $array = [];
            if (isset($gallery['modelId'])) {
                $array[] = $gallery['modelId'];
            } else {
                $array = $gallery['id'];
            }
            $payload[$gallery['field']] = $value;
            $this->galleryCatalogueRepository->updateByWhereIn('gallery_catalogues.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }

    private function updateGalleryCatalogue($id, $request)
    {
        // $galleryCatalogue = $this->galleryCatalogueRepository->findById($id);
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = json_encode($payload['album']);
        $flag = $this->galleryCatalogueRepository->update($id, $payload);
        return $flag;
    }

    private function createGalleryCatalogue($request)
    {
        $payload = $request->only($this->payload());
        // dd($payload);
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload['album']);
        // dd($payload);
        $galleryCatalogue = $this->galleryCatalogueRepository->create($payload);
        return $galleryCatalogue;
    }

    private function updateLanguageForCatalogue($galleryCatalogue, $request)
    {

        $payloadLanguage = $this->formatLanguagePayload($galleryCatalogue, $request);
        // dd($payloadLanguage);
        // dd($payloadLanguage['language_id']);
        $galleryCatalogue->languages()->detach([$payloadLanguage['language_id'], $galleryCatalogue->id]);
        $translate = $this->galleryCatalogueRepository->createPivot($galleryCatalogue, $payloadLanguage, 'languages');
        // dd($translate);
        return $translate;
    }

    private function formatLanguagePayload($galleryCatalogue, $request)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->currentLanguage();
        $payloadLanguage['gallery_catalogue_id'] = $galleryCatalogue->id;
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