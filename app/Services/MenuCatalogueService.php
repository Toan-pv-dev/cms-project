<?php

namespace App\Services;

use App\Models\MenuCatalogue;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\MenuCatalogueServiceInterface;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
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
class MenuCatalogueService  extends BaseService implements MenuCatalogueServiceInterface
{
    protected $menuCatalogueRepository;
    protected $languageRepository;
    // protected $nestedSet;
    protected $routerRepository;
    protected $controllerName = 'MenuCatalogueController';
    public function __construct(MenuCatalogueRepository $menuCatalogueRepository, RouterRepository $routerRepository, languageRepository $languageRepository)
    {
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedSet = new Nestedsetbie(
            [
                'table' => 'menu_catalogues',
                'foreignkey' => 'menu_catalogue_id',
                'language_id' => $this->currentLanguage(),
            ]
        );
    }
    public function select()
    {
        return ['id', 'name', 'publish', 'keyword'];
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
        $menuCatalogues = $this->menuCatalogueRepository->pagination(
            $select,
            $condition,
            $perpage,
            ['path' => 'menu/index'], // extend
            [], // orderBy
            [], // join
            [], // relations
            [],
            []  // rawQuery
        );

        return $menuCatalogues;
    }

    public function create($request)
    {
        // die();
        DB::beginTransaction();
        try {

            $payload = $request->only(['name', 'keyword']);
            $menuCatalogue = $this->menuCatalogueRepository->create($payload);
            DB::commit();

            return [
                'title' => $menuCatalogue->name,
                'id' => $menuCatalogue->id,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during menuCatalogue creation: ' . $e->getMessage());
            throw new \Exception('Error creating the menuCatalogue: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        // echo 1;
        // die();
        DB::beginTransaction();
        try {


            $menuCatalogue = $this->menuCatalogueRepository->findById($id);
            $flag = $this->updateMenuCatalogue($id, $request);
            if ($flag) {
                $this->updateLanguageForCatalogue($menuCatalogue, $request);
            }

            $this->updateRouter($menuCatalogue, $request, $this->controllerName, $this->currentLanguage());



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
            $menus = $this->menuCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByWhere([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controller\Frontend\\' . $this->controllerName],
            ]);
            DB::commit();
            return $menus;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }

    public function updateStatus($menuCatalogue = [])
    {
        DB::beginTransaction();
        try {
            // dd($menuCatalogue);
            $payload[$menuCatalogue['field']] = (($menuCatalogue['value'] == '1') ? '0' : '1');
            $this->menuCatalogueRepository->update($menuCatalogue['modelId'], $payload);
            $this->changeUserStatus($menuCatalogue, $payload[$menuCatalogue['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($menuCatalogue = [])
    {
        DB::beginTransaction();
        try {
            $payload[$menuCatalogue['field']] = $menuCatalogue['value'];
            $flag = $this->menuCatalogueRepository->updateByWhereIn('id', $menuCatalogue['id'], $payload);
            $this->changeUserStatus($menuCatalogue, $payload[$menuCatalogue['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function changeUserStatus($menuCatalogue,  $value)
    {
        // dd($menuCatalogue);
        DB::beginTransaction();
        try {
            $array = [];
            if (isset($menuCatalogue['modelId'])) {
                $array[] = $menuCatalogue['modelId'];
            } else {
                $array = $menuCatalogue['id'];
            }
            $payload[$menuCatalogue['field']] = $value;
            $this->menuCatalogueRepository->updateByWhereIn('menu_catalogues.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
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
