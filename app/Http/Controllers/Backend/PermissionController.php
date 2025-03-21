<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\PermissionServiceInterface as permissionService;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\Request;
use App\Repositories\PermissionRepository as permissionRepository;
use Illuminate\Support\Facades\App;

class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepository;

    public function __construct(permissionService $permissionService, permissionRepository $permissionRepository)
    {
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {
        $config = [
            'js' => [

                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',



            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ],
            'model' => 'permission'
        ];
        // echo 1;
        // die();
        $config['seo'] = __('messages.permission');
        // dd($config['seo']);
        // echo 1;
        // die();
        $permissions = $this->permissionService->paginate($request);
        // dd($users);

        $template = 'backend.permission.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'permissions',

        ));
    }


    public function create()
    {
        // dd($location);
        $config = $this->configData();
        $config['seo'] =  __('messages.permission');
        $config['method'] = 'create';
        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }
    public function store(StorePermissionRequest $storePermissionRequest)
    {
        // dd($storePermissionRequest->all());

        if ($this->permissionService->create($storePermissionRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('permission.index');
        }

        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('permission.index');
    }
    public function edit($id)
    {
        $permission = $this->permissionRepository->findById($id);
        $config = $this->configData();
        $config['seo'] =  __('messages.permission');
        $config['method'] = 'update';

        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'permission'
        ));
    }
    public function update(UpdatePermissionRequest $updaterequest, $id)
    {
        if ($this->permissionService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('permission.index');
        }

        return redirect()->route('permission.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $permission = $this->permissionRepository->findById($id);

        $config['seo'] =  __('messages.permission');
        $config['method'] = 'delete';

        $template = 'backend.permission.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'permission',
            'config'
        ));
    }
    public function destroy($id)
    {
        if ($this->permissionRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('permission.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('permission.index');
        }
    }
    private function configData()
    {
        return  [
            'js' => [
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/finder.js',
            ],
        ];
    }

    public function switchBackendPermission($id)
    {
        // dd($currentLang);
        $locale = $this->permissionRepository->findById($id);

        // dd($locale->canonical);
        if ($this->permissionService->switch($id)) {

            App::setLocale($locale->canonical);
            session(['locale' => $locale->canonical]);
        }
        // dd(App::getLocale());

        return redirect()->back();
    }
}
