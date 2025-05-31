<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\LanguageRepository as LanguageRepository;
use App\Repositories\AttributeCatalogueRepository as AttributeCatalogueRepository;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\DeleteProductRequest;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository as ProductRepository;
use App\Classes\Nestedsetbie;


class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $nestedset;
    protected $language;
    protected $languageRepository;
    protected $attributeCatalogueRepository;



    public function __construct(ProductService $productService, AttributeCatalogueRepository $attributeCatalogueRepository, ProductRepository $productRepository, LanguageRepository $languageRepository)
    {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
                'language_id' => 1,
            ]
        );
        $this->language = $this->currentLanguage();
        $this->languageRepository = $languageRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
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
            'model' => 'product'
        ];
        $config['seo'] = __('messages.product');

        // dd($config['seo']);
        $dropdown = $this->getDropdown();
        // $language = $this->languageRepository->all();
        // dd($language);
        $products = $this->productService->paginate($request);
        // dd($products);
        $template = 'backend.product.product.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'products',
            'dropdown',

        ));
    }

    public function create()
    {
        // dd($location);
        $attributeCatalogues = $this->attributeCatalogueRepository->getAll($this->currentLanguage());
        // dd($attributeCatalogues);

        $config = $this->configData();
        $config['seo'] = __('messages.product');
        $config['method'] = 'create';
        $dropdown = $this->getDropdown();
        $template = 'backend.product.product.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'attributeCatalogues'
        ));
    }
    public function store(StoreProductRequest $storeProductRequest)
    {

        if ($this->productService->create($storeProductRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('product.index');
        }
        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('product.index');
    }
    public function edit($id)
    {
        $attributeCatalogues = $this->attributeCatalogueRepository->getAll($this->currentLanguage());


        $product = $this->productRepository->getProductById($id, $this->language);
        $modelInstance = $product;
        $album = json_decode($product->album);
        $product_catalogue = $this->catalogue($product);
        $config = $this->configData();
        // dd($product_catalogue);
        $config['seo'] = __('messages.product');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();
        // $album = json_decode($product->album);
        $template = 'backend.product.product.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'product',
            'modelInstance',
            'dropdown',
            'album',
            'product_catalogue',
            'attributeCatalogues'
        ));
    }
    public function update($id, UpdateProductRequest $updaterequest,)
    {

        if ($this->productService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('product.index');
        }
        return redirect()->route('product.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $product = $this->productRepository->getProductById($id, $this->language);
        $config['seo'] = __('messages.product');
        $config['method'] = 'delete';
        // dd($product);
        $template = 'backend.product.product.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'product',
            'config'
        ));
    }
    public function destroy($id, DeleteProductRequest $request)
    {
        if ($this->productRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('product.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('product.index');
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
                '/backend/library/variant.js',
                '/backend/library/seo.js',
                '/backend/plugins/ckeditor/ckeditor.js',
                '/backend/plugins/nice-select/js/jquery.nice-select.js',

            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',
                '/backend/plugins/nice-select/css/nice-select.css',

            ]
        ];
    }
    private function catalogue($product)
    {
        $ids = $product->product_catalogues->pluck('id')->toArray();
        return $ids;
    }
}
