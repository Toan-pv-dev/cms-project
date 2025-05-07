<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\{ModuleTemplate}ServiceInterface as {moduleTemplate}Service;
use App\Http\Requests\Store{ModuleTemplate}Request;
use App\Http\Requests\Update{ModuleTemplate}Request;
use App\Http\Requests\Delete{ModuleTemplate}Request;
use Illuminate\Http\Request;
use App\Repositories\{ModuleTemplate}Repository as {moduleTemplate}Repository;
use App\Classes\Nestedsetbie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class {ModuleTemplate}Controller extends Controller
{
    use AuthorizesRequests;
    protected ${moduleTemplate}Service;
    protected ${moduleTemplate}Repository;
    protected $nestedset;
    protected $language;


    public function __construct({moduleTemplate}Service ${moduleTemplate}Service, {moduleTemplate}Repository ${moduleTemplate}Repository)
    {
        $this->{moduleTemplate}Service = ${moduleTemplate}Service;
        $this->{moduleTemplate}Repository = ${moduleTemplate}Repository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => '{table}',
                'foreignkey' => '{foreignKey}',
                'language_id' => $this->currentLanguage(),
            ]
        );
        $this->language = $this->currentLanguage();
    }

    private function getDropdown()
    {
        return $this->nestedset->Dropdown();
    }

    public function index(Request $request)
    {

        // dd(session()->all());


        // $this->authorize('modules', '{moduleView}.all');
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
            'model' => '{ModuleTemplate}'
        ];
        // echo 1;
        // die();
        $config['seo'] = config('apps.{moduleTemplate}');
        // dd($config['seo']);
        // echo 1;
        // die();
        ${moduleTemplate}s = $this->{moduleTemplate}Service->paginate($request);
        // dd($users);

        $template = 'backend.{moduleView}.index';
        // dd(${moduleTemplate}s);
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            '{moduleTemplate}s',

        ));
    }


    public function create()
    {
        // dd($location);
        // $this->authorize('modules', '{moduleView}.create');
        $config = $this->configData();
        $config['seo'] = config('apps.{moduleTemplate}');
        $config['method'] = 'create';
        // $album = json_decode(${moduleTemplate}->album);
        $dropdown = $this->getDropdown();
        $template = 'backend.{moduleView}.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(Store{ModuleTemplate}Request $store{ModuleTemplate}Request)
    {
        // dd($store{ModuleTemplate}Request->all());

        if ($this->{moduleTemplate}Service->create($store{ModuleTemplate}Request)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('{moduleView}.index');
        }

        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('{moduleView}.index');
    }
    public function edit($id)
    {
        // $this->authorize('modules', '{moduleView}.update');
        ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id, $this->language);
        // dd(${moduleTemplate});

        $config = $this->configData();
        $config['seo'] = config('apps.{moduleTemplate}');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();

        $album = json_decode(${moduleTemplate}->album);
        $template = 'backend.{moduleView}.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            '{moduleTemplate}',
            'dropdown',
            'album'
        ));
    }
    public function update(Update{ModuleTemplate}Request $updaterequest, $id)
    {

        if ($this->{moduleTemplate}Service->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('{moduleView}.index');
        }

        return redirect()->route('{moduleView}.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        // $this->authorize('modules', '{moduleView}.delete');

        ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id, $this->language);

        $config['seo'] = config('apps.{moduleTemplate}');
        $config['method'] = 'delete';
        // dd(${moduleTemplate});
        $template = 'backend.{moduleView}.delete';
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
            return redirect()->route('{moduleView}.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('{moduleView}.index');
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