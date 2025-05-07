<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\AttributeCatalogueServiceInterface as attributeCatalogueService;
use App\Http\Requests\StoreAttributeCatalogueRequest;
use App\Http\Requests\UpdateAttributeCatalogueRequest;
use App\Http\Requests\DeleteAttributeCatalogueRequest;
use Illuminate\Http\Request;
use App\Repositories\AttributeCatalogueRepository as attributeCatalogueRepository;
use App\Classes\Nestedsetbie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class AttributeCatalogueController extends Controller
{
    use AuthorizesRequests;
    protected $attributeCatalogueService;
    protected $attributeCatalogueRepository;
    protected $nestedset;
    protected $language;


    public function __construct(attributeCatalogueService $attributeCatalogueService, attributeCatalogueRepository $attributeCatalogueRepository)
    {
        $this->attributeCatalogueService = $attributeCatalogueService;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => 'attribute_catalogues',
                'foreignkey' => 'attribute_catalogue_id',
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

        $config = [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ],
            'model' => 'AttributeCatalogue'
        ];
        $config['seo'] = __('messages.attributeCatalogue');
        // dd($config['seo']);
        // echo 1;
        // die();
        $attributeCatalogues = $this->attributeCatalogueService->paginate($request);
        // dd($users);

        $template = 'backend.attribute.catalogue.index';
        // dd($attributeCatalogues);
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'attributeCatalogues',

        ));
    }


    public function create()
    {
        // dd($location);
        // $this->authorize('modules', 'attribute.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.attributeCatalogue');
        $config['method'] = 'create';
        // $album = json_decode($attributeCatalogue->album);
        $dropdown = $this->getDropdown();
        $template = 'backend.attribute.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(StoreAttributeCatalogueRequest $storeAttributeCatalogueRequest)
    {
        // dd($storeAttributeCatalogueRequest->all());

        if ($this->attributeCatalogueService->create($storeAttributeCatalogueRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('attribute.catalogue.index');
        }

        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('attribute.catalogue.index');
    }
    public function edit($id)
    {
        // $this->authorize('modules', 'attribute.catalogue.update');
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        // dd($attributeCatalogue);

        $config = $this->configData();
        $config['seo'] = __('messages.attributeCatalogue');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();

        $album = json_decode($attributeCatalogue->album);
        $template = 'backend.attribute.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'attributeCatalogue',
            'dropdown',
            'album'
        ));
    }
    public function update(UpdateAttributeCatalogueRequest $updaterequest, $id)
    {

        if ($this->attributeCatalogueService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('attribute.catalogue.index');
        }

        return redirect()->route('attribute.catalouge.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        // $this->authorize('modules', 'attribute.catalogue.delete');

        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);

        $config['seo'] = __('messages.attributeCatalogue');
        $config['method'] = 'delete';
        // dd($attributeCatalogue);
        $template = 'backend.attribute.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'attributeCatalogue',
            'config'
        ));
    }
    public function destroy($id, DeleteAttributeCatalogueRequest $request)
    {
        if ($this->attributeCatalogueRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('attribute.catalogue.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('attribute.catalogue.index');
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
