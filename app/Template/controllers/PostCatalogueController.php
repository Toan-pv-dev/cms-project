<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\FirstModelCatalogueServiceInterface as firstModelCatalogueService;
use App\Http\Requests\StoreFirstModelCatalogueRequest;
use App\Http\Requests\UpdateFirstModelCatalogueRequest;
use App\Http\Requests\DeleteFirstModelCatalogueRequest;
use Illuminate\Http\Request;
use App\Repositories\FirstModelCatalogueRepository as firstModelCatalogueRepository;
use App\Classes\Nestedsetbie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class FirstModelCatalogueController extends Controller
{
    use AuthorizesRequests;
    protected $firstModelCatalogueService;
    protected $firstModelCatalogueRepository;
    protected $nestedset;
    protected $language;


    public function __construct(firstModelCatalogueService $firstModelCatalogueService, firstModelCatalogueRepository $firstModelCatalogueRepository)
    {
        $this->firstModelCatalogueService = $firstModelCatalogueService;
        $this->firstModelCatalogueRepository = $firstModelCatalogueRepository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => 'firstModel_catalogues',
                'foreignkey' => 'firstModel_catalogue_id',
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
            'model' => 'FirstModelCatalogue'
        ];
        $config['seo'] = __('messages.firstModelCatalogue');
        // dd($config['seo']);
        // echo 1;
        // die();
        $firstModelCatalogues = $this->firstModelCatalogueService->paginate($request);
        // dd($users);

        $template = 'backend.firstModel.catalogue.index';
        // dd($firstModelCatalogues);
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'firstModelCatalogues',

        ));
    }


    public function create()
    {
        // dd($location);
        // $this->authorize('modules', 'firstModel.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.firstModelCatalogue');
        $config['method'] = 'create';
        // $album = json_decode($firstModelCatalogue->album);
        $dropdown = $this->getDropdown();
        $template = 'backend.firstModel.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(StoreFirstModelCatalogueRequest $storeFirstModelCatalogueRequest)
    {
        // dd($storeFirstModelCatalogueRequest->all());

        if ($this->firstModelCatalogueService->create($storeFirstModelCatalogueRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('firstModel.catalogue.index');
        }

        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('firstModel.catalogue.index');
    }
    public function edit($id)
    {
        // $this->authorize('modules', 'firstModel.catalogue.update');
        $firstModelCatalogue = $this->firstModelCatalogueRepository->getFirstModelCatalogueById($id, $this->language);
        // dd($firstModelCatalogue);

        $config = $this->configData();
        $config['seo'] = __('messages.firstModelCatalogue');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();

        $album = json_decode($firstModelCatalogue->album);
        $template = 'backend.firstModel.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'firstModelCatalogue',
            'dropdown',
            'album'
        ));
    }
    public function update(UpdateFirstModelCatalogueRequest $updaterequest, $id)
    {

        if ($this->firstModelCatalogueService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('firstModel.catalogue.index');
        }

        return redirect()->route('firstModel.catalouge.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        // $this->authorize('modules', 'firstModel.catalogue.delete');

        $firstModelCatalogue = $this->firstModelCatalogueRepository->getFirstModelCatalogueById($id, $this->language);

        $config['seo'] = __('messages.firstModelCatalogue');
        $config['method'] = 'delete';
        // dd($firstModelCatalogue);
        $template = 'backend.firstModel.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'firstModelCatalogue',
            'config'
        ));
    }
    public function destroy($id, DeleteFirstModelCatalogueRequest $request)
    {
        if ($this->firstModelCatalogueRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('firstModel.catalogue.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('firstModel.catalogue.index');
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
