<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\{ModuleTemplate}ServiceInterface as {ModuleTemplate}Service;
use App\Repositories\LanguageRepository as LanguageRepository;
use App\Http\Requests\Store{ModuleTemplate}Request;
use App\Http\Requests\Update{ModuleTemplate}Request;
use App\Http\Requests\Delete{ModuleTemplate}Request;
use Illuminate\Http\Request;
use App\Repositories\{ModuleTemplate}Repository as {ModuleTemplate}Repository;
use App\Classes\Nestedsetbie;


class {ModuleTemplate}Controller extends Controller
{
    protected ${moduleTemplate}Service;
    protected ${moduleTemplate}Repository;
    protected $nestedset;
    protected $language;
    protected $languageRepository;



    public function __construct({ModuleTemplate}Service ${moduleTemplate}Service, {ModuleTemplate}Repository ${moduleTemplate}Repository, LanguageRepository $languageRepository)
    {
        $this->{moduleTemplate}Service = ${moduleTemplate}Service;
        $this->{moduleTemplate}Repository = ${moduleTemplate}Repository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => '{moduleTemplate}_catalogues',
                'foreignkey' => '{moduleTemplate}_catalogue_id',
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
            'model' => '{moduleTemplate}'
        ];
        $config['seo'] = __('messages.{moduleTemplate}Catalogue');
        // dd($config['seo']);
        $dropdown = $this->getDropdown();
        // $language = $this->languageRepository->all();
        // dd($language);
        ${moduleTemplate}s = $this->{moduleTemplate}Service->paginate($request);
        // dd(${moduleTemplate}s);
        $template = 'backend.{moduleTemplate}.{moduleTemplate}.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            '{moduleTemplate}s',
            'dropdown',

        ));
    }

    public function create()
    {
        // dd($location);
        $config = $this->configData();
         $config['seo'] = __('messages.{moduleTemplate}');
        $config['method'] = 'create';
        $dropdown = $this->getDropdown();
        $template = 'backend.{moduleTemplate}.{moduleTemplate}.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(Store{ModuleTemplate}Request $store{ModuleTemplate}Request)
    {

        if ($this->{moduleTemplate}Service->create($store{ModuleTemplate}Request)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('{moduleTemplate}.index');
        }
        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('{moduleTemplate}.index');
    }
    public function edit($id)
    {

        ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id, $this->language);
        $album = json_decode(${moduleTemplate}->album);

        ${moduleTemplate}_catalogue = $this->catalogue(${moduleTemplate});

        $config = $this->configData();
        // dd(${moduleTemplate}_catalogue);
         $config['seo'] = __('messages.{moduleTemplate}');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();
        // $album = json_decode(${moduleTemplate}->album);
        $template = 'backend.{moduleTemplate}.{moduleTemplate}.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            '{moduleTemplate}',
            'dropdown',
            'album',
            '{moduleTemplate}_catalogue'
        ));
    }
    public function update($id, Update{ModuleTemplate}Request $updaterequest,)
    {
        if ($this->{moduleTemplate}Service->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('{moduleTemplate}.index');
        }
        return redirect()->route('{moduleTemplate}.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id, $this->language);
         $config['seo'] = __('messages.{moduleTemplate}');
        $config['method'] = 'delete';
        // dd(${moduleTemplate});
        $template = 'backend.{moduleTemplate}.{moduleTemplate}.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            '{moduleTemplate}',
            'config'
        ));
    }
    public function destroy($id, Delete{ModuleTemplate}Request $request)
    {
        if ($this->{moduleTemplate}Repository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('{moduleTemplate}.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('{moduleTemplate}.index');
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
    private function catalogue(${moduleTemplate})
    {
        $ids = ${moduleTemplate}->{moduleTemplate}_catalogues->pluck('id')->toArray();
        return $ids;
    }
}