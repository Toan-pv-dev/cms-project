<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\Interfaces\GenerateServiceInterface as generateService;
use App\Http\Requests\StoreGenerateRequest;
use App\Http\Requests\UpdateGenerateRequest;
use App\Http\Requests\StoreTranslateRequest;
use Illuminate\Http\Request;
use App\Repositories\GenerateRepository as generateRepository;
use Illuminate\Support\Facades\App;
use App\Models\PostCatalogue;

class GenerateController extends Controller
{
    use AuthorizesRequests;
    protected $generateService;
    protected $generateRepository;


    public function __construct(generateService $generateService, generateRepository $generateRepository)
    {
        $this->generateService = $generateService;
        $this->generateRepository = $generateRepository;
    }

    public function index(Request $request)
    {


        $this->authorize('modules', 'generate.index');
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
            'model' => 'generate'
        ];
        // echo 1;
        // die();
        $config['seo'] = __('messages.generate');
        // dd($config['seo']);
        // echo 1;P
        // die();
        $generates = $this->generateService->paginate($request);
        // dd($users);

        $template = 'backend.generate.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'generates',

        ));
    }


    public function create()
    {
        // dd($location);
        $config = $this->configData();
        $config['seo'] =  __('messages.generate');
        $config['method'] = 'create';

        $template = 'backend.generate.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }
    public function store(StoreGenerateRequest $storeGenerateRequest)
    {
        // dd($storeGenerateRequest->all());

        if ($this->generateService->create($storeGenerateRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('generate.index');
        }

        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('generate.index');
    }
    public function edit($id)
    {
        $generate = $this->generateRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = config('apps.generate');
        $config['method'] = 'update';

        $template = 'backend.generate.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'generate'
        ));
    }
    public function update(UpdateGenerateRequest $updaterequest, $id)
    {
        if ($this->generateService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('generate.index');
        }

        return redirect()->route('generate.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $generate = $this->generateRepository->findById($id);

        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'delete';

        $template = 'backend.generate.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'generate',
            'config'
        ));
    }
    public function destroy($id)
    {
        if ($this->generateRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('generate.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('generate.index');
        }
    }
    private function configData()
    {
        return  [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',

            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',

            ]
        ];
    }

    public function switchBackendGenerate($id)
    {
        // dd($currentLang);
        $locale = $this->generateRepository->findById($id);
        // dd($locale->canonical);
        if ($this->generateService->switch($id)) {

            App::setLocale($locale->canonical);
            session(['locale' => $locale->canonical]);
        }
        // dd(App::getLocale());

        return redirect()->back();
    }
    public function translate($id, int $generateId, $model)
    {
        $repositoryInstance = $this->repoInstance($model);
        $generateInstance = $this->repoInstance('generate');
        $currentGenerate = $generateInstance->findByCondition([
            ['canonical', '=', App::getLocale()]
        ]);

        $methodName = 'get' . $model . 'ById';
        $modelInstance = $repositoryInstance->{$methodName}($id, $currentGenerate->id);
        $modelTranslate = $repositoryInstance->getPostCatalogueById($id, $generateId);
        // dd($modelTranslate);
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

            ]
        ];
        $config['seo'] = config('apps.usercatalogue');
        $option = [
            'id' => $id,
            'generateId' => $generateId,
            'model' => $model
        ];
        $template = 'backend.generate.translate';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'modelInstance',
            'modelTranslate',
            'option'
        ));
    }
    private function repoInstance($model)
    {
        $repoName = ucfirst($model) . 'Repository';
        $serviceInterfaceNamespace = '\App\Repositories\\' . $repoName;
        if (class_exists($serviceInterfaceNamespace)) {
            $repositoryInstance = app($serviceInterfaceNamespace);
        }
        return $repositoryInstance;
    }
    public function storeTranslate(StoreTranslateRequest $request)
    {

        // dd($request->all());
        $option = ($request->input('option'));

        if ($this->generateService->saveTranslate($option, $request)) {
            flash()->success('Thêm mới bản ghi thành công');
            return redirect()->route('post.catalogue.index');
        }
        flash()->error('Thêm mới bản ghi không thành công');
        return redirect()->route('post.catalogue.index');
    }
}
