<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\UserCatalogueServiceInterface as userCatalogueService;
// use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as userCatalogueRepositoryInterface;
use App\Http\Requests\StoreUserCatalogueRequest;
use App\Http\Requests\UpdateUserCatalogueRequest;
use Illuminate\Http\Request;
// use App\Http\Requests\UpdateUserCatalogueRequest;
use App\Repositories\UserCatalogueRepository as userCatalogueRepository;
use App\Repositories\PermissionRepository as permissionRepository;

class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;
    protected $permissionRepository;

    public function __construct(userCatalogueService $userCatalogueService, userCatalogueRepository $userCatalogueRepository, permissionRepository $permissionRepository)
    {
        $this->userCatalogueService = $userCatalogueService;
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {



        // dd(config('apps.usercatalogue'));
        $config = [
            'js' => [

                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',



            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ],
            'model' => 'UserCatalogue'
        ];
        // echo 1;
        // die();
        $config['seo'] = config('apps.usercatalogue');
        // dd($config['seo']);
        // echo 1;
        // die();
        $user_catalogues = $this->userCatalogueService->paginate($request);
        // dd($users);

        $template = 'backend.user.catalogue.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'user_catalogues',

        ));
    }


    public function create()
    {
        // dd($location);
        $config = [
            'js' => [
                '/backend/library/location.js',
                '/backend/plugins/ckfinder/ckfinder.js',
                '/backend/library/finder.js',
            ],
        ];
        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'create';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }
    public function store(StoreUserCatalogueRequest $storeusercataloguerequest)
    {
        // dd($storeusercataloguerequest);
        if ($this->userCatalogueService->create($storeusercataloguerequest)) {
            flash()->success('them ban ghi thanh cong');
            return redirect()->route('user.catalogue.index');
        }

        return redirect()->route('user.catalogue.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function edit($id)
    {
        $user_catalogue = $this->userCatalogueRepository->findById($id);
        $config = [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                '/backend/library/finder.js',
            ],
            'css' => [
                'css' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'update';

        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'user_catalogue'
        ));
    }
    public function update(UpdateUserCatalogueRequest $updaterequest, $id)
    {
        if ($this->userCatalogueService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('user.catalogue.index');
        }

        return redirect()->route('user.catalouge.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $user_catalogue = $this->userCatalogueRepository->findById($id);

        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'delete';

        $template = 'backend.user.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'user_catalogue',
            'config'
        ));
    }
    public function destroy($id)
    {
        if ($this->userCatalogueRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('user.catalogue.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('user.catalogue.index');
        }
    }
    public function permission()
    {
        $userCatalogues = $this->userCatalogueRepository->all(['permissions']);
        $permissions = $this->permissionRepository->all();
        $config['seo'] = __('messages.userCatalogue');
        $template = 'backend.user.catalogue.permission';
        return view('backend.dashboard.layout', compact(
            'template',
            'userCatalogues',
            'permissions',
            'config'
        ));
    }

    public function updatePermission(Request $request)
    {
        if ($this->userCatalogueService->setPermission($request)) {
            flash()->success('Cập nhật quyền thành công');
            return redirect()->route('user.catalogue.index');
        }
        flash()->error('Cập nhật quyền không thành công');
        return redirect()->route('user.catalogue.index');
    }
}
