<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\AttributeServiceInterface as AttributeService;
use App\Repositories\LanguageRepository as LanguageRepository;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Requests\DeleteAttributeRequest;
use Illuminate\Http\Request;
use App\Repositories\AttributeRepository as AttributeRepository;
use App\Classes\Nestedsetbie;


class AttributeController extends Controller
{
    protected $attributeService;
    protected $attributeRepository;
    protected $nestedset;
    protected $language;
    protected $languageRepository;



    public function __construct(AttributeService $attributeService, AttributeRepository $attributeRepository, LanguageRepository $languageRepository)
    {
        $this->attributeService = $attributeService;
        $this->attributeRepository = $attributeRepository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => 'attribute_catalogues',
                'foreignkey' => 'attribute_catalogue_id',
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
            'model' => 'attribute'
        ];
        $config['seo'] = __('messages.attribute');
        // dd($config['seo']);
        $dropdown = $this->getDropdown();
        // $language = $this->languageRepository->all();
        // dd($language);
        $attributes = $this->attributeService->paginate($request);
        // dd($attributes);
        $template = 'backend.attribute.attribute.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'attributes',
            'dropdown',

        ));
    }

    public function create()
    {
        // dd($location);
        $config = $this->configData();
        $config['seo'] = __('messages.attribute');
        $config['method'] = 'create';
        $dropdown = $this->getDropdown();
        $template = 'backend.attribute.attribute.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(StoreAttributeRequest $storeAttributeRequest)
    {

        if ($this->attributeService->create($storeAttributeRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('attribute.index');
        }
        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('attribute.index');
    }
    public function edit($id)
    {

        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        $album = json_decode($attribute->album);

        $attribute_catalogue = $this->catalogue($attribute);

        $config = $this->configData();
        // dd($attribute_catalogue);
        $config['seo'] = __('messages.attribute');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();
        // $album = json_decode($attribute->album);
        $template = 'backend.attribute.attribute.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'attribute',
            'dropdown',
            'album',
            'attribute_catalogue'
        ));
    }
    public function update($id, UpdateAttributeRequest $updaterequest,)
    {
        if ($this->attributeService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('attribute.index');
        }
        return redirect()->route('attribute.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        $config['seo'] = __('messages.attribute');
        $config['method'] = 'delete';

        // dd($attribute);
        $template = 'backend.attribute.attribute.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'attribute',
            'config'
        ));
    }
    public function destroy($id)
    {
        // dd($id);
        if ($this->attributeService->destroy($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('attribute.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('attribute.index');
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
    private function catalogue($attribute)
    {
        $ids = $attribute->attribute_catalogues->pluck('id')->toArray();
        return $ids;
    }

    // public function getAttribute(Request $request)
    // {
    //     $payload = $request->input();
    //     $attributes = $this->attributeRepository->searchAttributes($payload['search'], $payload['option'], $this->language);
    //     $attributeMapped = $attributes->map(function ($attribute) {
    //         return [
    //             'id' => $attribute->id,
    //             'text' => $attribute->attribute_language->first()->name,
    //         ];
    //     })->all();
    //     return response()->json(array('item' => $attributeMapped));
    // }
}
