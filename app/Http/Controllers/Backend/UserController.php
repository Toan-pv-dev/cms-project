<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\UserServiceInterface as UserService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceService;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository as RepositoriesUserRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;


class UserController extends Controller
{
    use AuthorizesRequests;
    protected $userService;
    protected $provinceRepository;
    protected $userRepository;

    public function __construct(UserService $userService, ProvinceService $provinceRepository, RepositoriesUserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->provinceRepository = $provinceRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {

        // dd(config('apps.module'));
        // dd(config('apps.user'));
        // if (!Gate::allows('modules', 'user.catalogue.index')) {
        //     abort(403, 'Unauthorized action.');
        // }

        // $this->authorize('modules', 'post.catalogue.index');

        $config = [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/finder.js',
                '/backend/library/seo.js',
                '/backend/plugins/ckeditor/ckeditor.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ],
            'model' => 'User'
        ];
        $config['seo'] = config('apps.user');
        $users = $this->userService->paginate($request);
        // dd($users);
        // dd($users);
        $template = 'backend.user.user.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'users',

        ));
    }


    public function create()
    {
        $config = [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/finder.js',
                '/backend/library/seo.js',
                '/backend/plugins/ckeditor/ckeditor.js',
                '/backend/library/location.js',

            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ],
            'model' => 'User'
        ];
        $provinces = $this->provinceRepository->all();
        // dd($location);
        // $config = $this->configData();
        $config['seo'] = config('apps.user');
        $config['method'] = 'create';
        $template = 'backend.user.user.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'provinces'
        ));
    }
    public function store(StoreUserRequest $storerequest)
    {
        // dd($storerequest);
        if ($this->userService->create($storerequest)) {
            flash()->success('them ban ghi thanh cong');
            return redirect()->route('user.index');
        }

        return redirect()->route('user.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function edit($id)
    {
        $user = $this->userRepository->findById($id);
        $provinces = $this->provinceRepository->all();
        $config = $this->configData();
        $config['seo'] = config('apps.user');
        $config['method'] = 'update';

        $template = 'backend.user.user.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'provinces',
            'user'
        ));
    }
    public function update(UpdateUserRequest $updaterequest, $id)
    {
        // dd($updaterequest);
        if ($this->userService->update($id, $updaterequest)) {
            flash()->success('them ban ghi thanh cong');
            return redirect()->route('user.index');
        }

        return redirect()->route('user.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $user = $this->userRepository->findById($id);

        $config['seo'] = config('apps.user');
        $config['method'] = 'delete';
        // $config['method'] = 'delete';


        $template = 'backend.user.user.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'user',
            'config'
        ));
    }
    public function destroy($id)
    {
        if ($this->userRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('user.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('user.index');
        }
    }
    private function configData()
    {
        return  [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/finder.js',
                '/backend/library/seo.js',
                '/backend/plugins/ckeditor/ckeditor.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ]
        ];
    }
}
