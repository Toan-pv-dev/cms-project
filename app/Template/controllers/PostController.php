<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\ControllerNameServiceInterface as ControllerNameService;
use App\Repositories\LanguageRepository as LanguageRepository;
use App\Http\Requests\StoreControllerNameRequest;
use App\Http\Requests\UpdateControllerNameRequest;
use App\Http\Requests\DeleteControllerNameRequest;
use Illuminate\Http\Request;
use App\Repositories\ControllerNameRepository as ControllerNameRepository;
use App\Classes\Nestedsetbie;


class ControllerNameController extends Controller
{
    protected $controllerNameService;
    protected $controllerNameRepository;
    protected $nestedset;
    protected $language;
    protected $languageRepository;



    public function __construct(ControllerNameService $controllerNameService, ControllerNameRepository $controllerNameRepository, LanguageRepository $languageRepository)
    {
        $this->controllerNameService = $controllerNameService;
        $this->controllerNameRepository = $controllerNameRepository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => 'controllerName_catalogues',
                'foreignkey' => 'controllerName_catalogue_id',
                'language_id' => 1,
            ]
        );
        $this->language = $this->currentLanguage();
        $this->languageRepository = $languageRepository;
    }
    private function getDropdown()
    {
        return $this->nestedset->Dropdown();
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
            'model' => 'controllerName'
        ];
        $config['seo'] = __('messages.controllerName');
        // dd($config['seo']);
        $dropdown = $this->getDropdown();
        // $language = $this->languageRepository->all();
        // dd($language);
        $controllerNames = $this->controllerNameService->paginate($request);
        // dd($controllerNames);
        $template = 'backend.controllerName.controllerName.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'controllerNames',
            'dropdown',

        ));
    }

    public function create()
    {
        // dd($location);
        $config = $this->configData();
        $config['seo'] = config('apps.controllerName');
        $config['method'] = 'create';
        $dropdown = $this->getDropdown();
        $template = 'backend.controllerName.controllerName.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(StoreControllerNameRequest $storeControllerNameRequest)
    {

        if ($this->controllerNameService->create($storeControllerNameRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('controllerName.index');
        }
        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('controllerName.index');
    }
    public function edit($id)
    {

        $controllerName = $this->controllerNameRepository->getControllerNameById($id, $this->language);
        $album = json_decode($controllerName->album);

        $controllerName_catalogue = $this->catalogue($controllerName);

        $config = $this->configData();
        // dd($controllerName_catalogue);
        $config['seo'] = config('apps.controllerName');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();
        // $album = json_decode($controllerName->album);
        $template = 'backend.controllerName.controllerName.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'controllerName',
            'dropdown',
            'album',
            'controllerName_catalogue'
        ));
    }
    public function update($id, UpdateControllerNameRequest $updaterequest,)
    {
        if ($this->controllerNameService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('controllerName.index');
        }
        return redirect()->route('controllerName.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $controllerName = $this->controllerNameRepository->getControllerNameById($id, $this->language);
        $config['seo'] = config('apps.controllerName');
        $config['method'] = 'delete';
        // dd($controllerName);
        $template = 'backend.controllerName.controllerName.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'controllerName',
            'config'
        ));
    }
    public function destroy($id, DeleteControllerNameRequest $request)
    {
        if ($this->controllerNameRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('controllerName.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('controllerName.index');
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
    private function catalogue($controllerName)
    {
        $ids = $controllerName->controllerName_catalogues->pluck('id')->toArray();
        return $ids;
    }
}