<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\ProductCatalogueServiceInterface as productCatalogueService;
use App\Http\Requests\StoreProductCatalogueRequest;
use App\Http\Requests\UpdateProductCatalogueRequest;
use App\Http\Requests\DeleteProductCatalogueRequest;
use Illuminate\Http\Request;
use App\Repositories\ProductCatalogueRepository as productCatalogueRepository;
use App\Classes\Nestedsetbie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class ProductCatalogueController extends Controller
{
    use AuthorizesRequests;
    protected $productCatalogueService;
    protected $productCatalogueRepository;
    protected $nestedset;
    protected $language;


    public function __construct(productCatalogueService $productCatalogueService, productCatalogueRepository $productCatalogueRepository)
    {
        $this->productCatalogueService = $productCatalogueService;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
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
            'model' => 'ProductCatalogue'
        ];
        $config['seo'] = __('messages.productCatalogue');
        // dd($config['seo']);
        // echo 1;
        // die();
        $productCatalogues = $this->productCatalogueService->paginate($request);
        // dd($users);

        $template = 'backend.product.catalogue.index';
        // dd($productCatalogues);
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'productCatalogues',

        ));
    }


    public function create()
    {
        // dd($location);
        // $this->authorize('modules', 'product.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.productCatalogue');
        $config['method'] = 'create';
        // $album = json_decode($productCatalogue->album);
        $dropdown = $this->getDropdown();
        $template = 'backend.product.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(StoreProductCatalogueRequest $storeProductCatalogueRequest)
    {
        // dd($storeProductCatalogueRequest->all());

        if ($this->productCatalogueService->create($storeProductCatalogueRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('product.catalogue.index');
        }

        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('product.catalogue.index');
    }
    public function edit($id)
    {
        // $this->authorize('modules', 'product.catalogue.update');
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);
        // dd($productCatalogue);

        $config = $this->configData();
        $config['seo'] = __('messages.productCatalogue');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();

        $album = json_decode($productCatalogue->album);
        $template = 'backend.product.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'productCatalogue',
            'dropdown',
            'album'
        ));
    }
    public function update(UpdateProductCatalogueRequest $updaterequest, $id)
    {

        if ($this->productCatalogueService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('product.catalogue.index');
        }

        return redirect()->route('product.catalouge.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        // $this->authorize('modules', 'product.catalogue.delete');

        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);

        $config['seo'] = __('messages.productCatalogue');
        $config['method'] = 'delete';
        // dd($productCatalogue);
        $template = 'backend.product.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'productCatalogue',
            'config'
        ));
    }
    public function destroy($id, DeleteProductCatalogueRequest $request)
    {
        if ($this->productCatalogueRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('product.catalogue.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('product.catalogue.index');
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
