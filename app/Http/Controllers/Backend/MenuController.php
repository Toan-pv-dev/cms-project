<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\MenuServiceInterface as MenuService;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceService;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\SaveChildrenMenuRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateMenuRequest;
use App\Repositories\MenuRepository as RepositoriesMenuRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use App\Helpers\MyHelper;


class MenuController extends Controller
{
    use AuthorizesRequests;
    protected $menuService;
    protected $provinceRepository;
    protected $menuRepository;
    protected $menuCatalogueRepository;
    protected $menuCatalogueService;

    public function __construct(MenuService $menuService, ProvinceService $provinceRepository, RepositoriesMenuRepository $menuRepository, MenuCatalogueRepository $menuCatalogueRepository, MenuCatalogueService $menuCatalogueService)
    {
        $this->menuService = $menuService;
        $this->provinceRepository = $provinceRepository;
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuCatalogueService = $menuCatalogueService;
    }

    public function index(Request $request)
    {


        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['model'] = 'MenuCatalogue';

        $menuCatalogues = $this->menuCatalogueService->paginate($request);

        $template = 'backend.menu.menu.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuCatalogues',

        ));
    }




    public function create()
    {

        // dd($location);
        // $config = $this->configData();
        $menuCatalogue = $this->menuCatalogueRepository->all();

        $config = $this->config();

        $config['seo'] = __('messages.menu');

        $config['method'] = 'create';
        $template = 'backend.menu.menu.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuCatalogue',
        ));
    }

    public function edit($id)
    {


        $menus = $this->menuRepository->findByCondition([
            ['menu_catalogue_id', '=', $id]
        ], true, ['languages'], 'order', 'desc');


        $a = (recursive($menus));
        (recursive_menu($a));
        // dd($menus);
        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['model'] = 'MenuCatalogue';
        $config['method'] = 'show';
        // $menuCatalogues = $this->menuCatalogueService->paginate($request);

        $template = 'backend.menu.menu.show';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menus',
            'id'

        ));
    }

    public function editMenu($id)
    {
        $menuCatalogue = $this->menuCatalogueRepository->all();
        $menus = $this->menuRepository->findByCondition([
            ['menu_catalogue_id', '=', $id],
            ['parent_id', '=', 0]
        ], true, ['languages'], 'order', 'desc');
        $catalogue_id = $id;
        $menus = $this->menuService->convertMenuToFormArray($menus);
        $config = $this->config();

        $template = 'backend.menu.menu.store';
        $config['seo'] = __('messages.menu');
        $config['model'] = 'Menu';
        $config['method'] = 'editMenu';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'id',
            'menuCatalogue',
            'menus',

        ));
    }

    public function showChildren($id)
    {

        // dd($location);
        // $config = $this->configData();
        $menuCatalogue = $this->menuCatalogueRepository->all();
        $language = $this->currentLanguage();

        $menu = $this->menuRepository->findById($id, ['*'], [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ]);

        $menuChildren = $this->convertMenu($menu, $this->currentLanguage());
        $config = $this->config();

        $config['seo'] = __('messages.menu');

        $config['method'] = 'create';
        $template = 'backend.menu.menu.children';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuCatalogue',
            'menu',
            'menuChildren'

        ));
    }

    public function saveChildren(SaveChildrenMenuRequest $storerequest, $id)
    {


        $menu = $this->menuRepository->findById($id);
        if ($this->menuService->saveChildren($storerequest, $this->currentLanguage(), $menu)) {
            flash()->success('them ban ghi thanh cong');
            return redirect()->route('menu.edit', $menu->menu_catalogue_id);
        }

        return redirect()->route('menu.edit', $menu->menu_catalogue_id)->with('error', 'Thêm mới bản ghi không thành công');
    }

    public function convertMenu($menu, $language = 1)
    {
        $menuChildren = $this->menuRepository->findByCondition(
            [['parent_id', '=', $menu->id]],
            true,
            ['languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }]
        );
        $temp = [];
        $fields = ['name', 'canonical', 'order', 'id'];
        if (count($menuChildren)) {
            foreach ($menuChildren as $key => $val) {
                foreach ($fields as $field) {
                    if ($field == 'name' || $field == 'canonical') {
                        $temp[$field][] = $val->languages->first()->pivot->{$field};
                    } else
                        $temp[$field][] = $val->{$field};
                }
            }
        }
        return $temp;
    }

    public function store(StoreMenuRequest $storerequest)
    {


        if ($this->menuService->create($storerequest, $this->currentLanguage())) {
            flash()->success('them ban ghi thanh cong');
            return redirect()->route('menu.index');
        }

        return redirect()->route('menu.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    // public function edit($id)
    // {
    //     $menu = $this->menuRepository->findById($id);
    //     $provinces = $this->provinceRepository->all();
    //     $config = $this->configData();
    //     $config['seo'] = config('apps.menu');
    //     $config['method'] = 'update';

    //     $template = 'backend.menu.menu.store';
    //     return view('backend.dashboard.layout', compact(
    //         'template',
    //         'config',
    //         'provinces',
    //         'menu'
    //     ));
    // }
    public function delete($id)
    {
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);
        $menu = $this->menuRepository->findById($id);
        $config = $this->config();

        $config['seo'] = __('messages.menu');
        $config['method'] = 'delete';


        $template = 'backend.menu.menu.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'menu',
            'config',
            'menuCatalogue'
        ));
    }
    public function destroy($id)
    {
        if ($this->menuService->destroy($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('menu.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('menu.index');
        }
    }
    private function config()
    {
        return [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/finder.js',
                '/backend/library/seo.js',
                '/backend/plugins/ckeditor/ckeditor.js',
                '/backend/library/menu.js',
                '/backend/js/plugins/nestable/jquery.nestable.js',

            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ],
            'model' => 'Menu'
        ];
    }
}
