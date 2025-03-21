<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\LanguageServiceInterface as languageService;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\StoreTranslateRequest;
use Illuminate\Http\Request;
use App\Repositories\LanguageRepository as languageRepository;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    protected $languageService;
    protected $languageRepository;


    public function __construct(languageService $languageService, languageRepository $languageRepository)
    {
        $this->languageService = $languageService;
        $this->languageRepository = $languageRepository;
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
            'model' => 'language'
        ];
        // echo 1;
        // die();
        $config['seo'] = config('apps.language');
        // dd($config['seo']);
        // echo 1;P
        // die();
        $languages = $this->languageService->paginate($request);
        // dd($users);

        $template = 'backend.language.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'languages',

        ));
    }


    public function create()
    {
        // dd($location);
        $config = $this->configData();
        $config['seo'] = config('apps.language');
        $config['method'] = 'create';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }
    public function store(StoreLanguageRequest $storeLanguageRequest)
    {
        // dd($storeLanguageRequest->all());

        if ($this->languageService->create($storeLanguageRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('language.index');
        }

        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('language.index');
    }
    public function edit($id)
    {
        $language = $this->languageRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = config('apps.language');
        $config['method'] = 'update';

        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'language'
        ));
    }
    public function update(UpdateLanguageRequest $updaterequest, $id)
    {
        if ($this->languageService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('language.index');
        }

        return redirect()->route('language.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $language = $this->languageRepository->findById($id);

        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'delete';

        $template = 'backend.language.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'language',
            'config'
        ));
    }
    public function destroy($id)
    {
        if ($this->languageRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('language.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('language.index');
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

    public function switchBackendLanguage($id)
    {
        // dd($currentLang);
        $locale = $this->languageRepository->findById($id);

        // dd($locale->canonical);
        if ($this->languageService->switch($id)) {

            App::setLocale($locale->canonical);
            session(['locale' => $locale->canonical]);
        }
        // dd(App::getLocale());

        return redirect()->back();
    }
    public function translate($id, int $languageId, $model)
    {
        $repositoryInstance = $this->repoInstance($model);
        $languageInstance = $this->repoInstance('language');
        $currentLanguage = $languageInstance->findByCondition([
            ['canonical', '=', App::getLocale()]
        ]);

        $methodName = 'get' . $model . 'ById';
        $modelInstance = $repositoryInstance->{$methodName}($id, $currentLanguage->id);
        $modelTranslate = $repositoryInstance->getPostCatalogueById($id, $languageId);
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
            'languageId' => $languageId,
            'model' => $model
        ];
        $template = 'backend.language.translate';
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

        if ($this->languageService->saveTranslate($option, $request)) {
            flash()->success('Thêm mới bản ghi thành công');
            return redirect()->back();
        }
        flash()->error('Thêm mới bản ghi không thành công');
        return redirect()->back();
    }
}
