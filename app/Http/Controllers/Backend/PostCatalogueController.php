<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\PostCatalogueServiceInterface as postCatalogueService;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use App\Http\Requests\DeletePostCatalogueRequest;
use Illuminate\Http\Request;
use App\Repositories\PostCatalogueRepository as postCatalogueRepository;
use App\Classes\Nestedsetbie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class PostCatalogueController extends Controller
{
    use AuthorizesRequests;
    protected $postCatalogueService;
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $language;


    public function __construct(postCatalogueService $postCatalogueService, postCatalogueRepository $postCatalogueRepository)
    {
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
                'language_id' => 1,
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


        // $this->authorize('modules', 'post.catalouge.all');
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
            'model' => 'PostCatalogue'
        ];
        // echo 1;
        // die();
        $config['seo'] = config('apps.postcatalogue');
        // dd($config['seo']);
        // echo 1;
        // die();
        $postCatalogues = $this->postCatalogueService->paginate($request);
        // dd($users);

        $template = 'backend.post.catalogue.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogues',

        ));
    }


    public function create()
    {
        // dd($location);
        // $this->authorize('modules', 'post.catalogue.create');
        $config = $this->configData();
        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'create';
        // $album = json_decode($postCatalogue->album);
        $dropdown = $this->getDropdown();
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(StorePostCatalogueRequest $storePostCatalogueRequest)
    {
        // dd($storePostCatalogueRequest->all());

        if ($this->postCatalogueService->create($storePostCatalogueRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('post.catalogue.index');
        }

        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('post.catalogue.index');
    }
    public function edit($id)
    {
        // $this->authorize('modules', 'post.catalogue.update');
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        // dd($postCatalogue);

        $config = $this->configData();
        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();

        $album = json_decode($postCatalogue->album);
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogue',
            'dropdown',
            'album'
        ));
    }
    public function update(UpdatePostCatalogueRequest $updaterequest, $id)
    {

        if ($this->postCatalogueService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('post.catalogue.index');
        }

        return redirect()->route('post.catalouge.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        // $this->authorize('modules', 'post.catalogue.delete');

        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);

        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'delete';
        // dd($postCatalogue);
        $template = 'backend.post.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'postCatalogue',
            'config'
        ));
    }
    public function destroy($id, DeletePostCatalogueRequest $request)
    {
        if ($this->postCatalogueRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('post.catalogue.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('post.catalogue.index');
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
