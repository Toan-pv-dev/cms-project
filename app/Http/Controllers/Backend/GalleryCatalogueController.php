<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\GalleryCatalogueServiceInterface as galleryCatalogueService;
use App\Http\Requests\StoreGalleryCatalogueRequest;
use App\Http\Requests\UpdateGalleryCatalogueRequest;
use App\Http\Requests\DeleteGalleryCatalogueRequest;
use Illuminate\Http\Request;
use App\Repositories\GalleryCatalogueRepository as galleryCatalogueRepository;
use App\Classes\Nestedsetbie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class GalleryCatalogueController extends Controller
{
    use AuthorizesRequests;
    protected $galleryCatalogueService;
    protected $galleryCatalogueRepository;
    protected $nestedset;
    protected $language;


    public function __construct(galleryCatalogueService $galleryCatalogueService, galleryCatalogueRepository $galleryCatalogueRepository)
    {
        $this->galleryCatalogueService = $galleryCatalogueService;
        $this->galleryCatalogueRepository = $galleryCatalogueRepository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => 'gallery_catalogues',
                'foreignkey' => 'gallery_catalogue_id',
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


        // $this->authorize('modules', 'gallery.catalogue.all');
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
            'model' => 'GalleryCatalogue'
        ];
        // echo 1;
        // die();
        $config['seo'] = __('messages.galleryCatalogue');
        // dd($config['seo']);
        // echo 1;
        // die();
        $galleryCatalogues = $this->galleryCatalogueService->paginate($request);
        // dd($users);

        $template = 'backend.gallery.catalogue.index';
        // dd($galleryCatalogues);
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'galleryCatalogues',

        ));
    }


    public function create()
    {
        // dd($location);
        // $this->authorize('modules', 'gallery.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.galleryCatalogue');
        $config['method'] = 'create';
        // $album = json_decode($galleryCatalogue->album);
        $dropdown = $this->getDropdown();
        $template = 'backend.gallery.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(StoreGalleryCatalogueRequest $storeGalleryCatalogueRequest)
    {
        // dd($storeGalleryCatalogueRequest->all());

        if ($this->galleryCatalogueService->create($storeGalleryCatalogueRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('gallery.catalogue.index');
        }

        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('gallery.catalogue.index');
    }
    public function edit($id)
    {
        // $this->authorize('modules', 'gallery.catalogue.update');
        $galleryCatalogue = $this->galleryCatalogueRepository->getGalleryCatalogueById($id, $this->language);
        // dd($galleryCatalogue);

        $config = $this->configData();
        $config['seo'] = __('messages.galleryCatalogue');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();

        $album = json_decode($galleryCatalogue->album);
        $template = 'backend.gallery.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'galleryCatalogue',
            'dropdown',
            'album'
        ));
    }
    public function update(UpdateGalleryCatalogueRequest $updaterequest, $id)
    {

        if ($this->galleryCatalogueService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('gallery.catalogue.index');
        }

        return redirect()->route('gallery.catalogue.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        // $this->authorize('modules', 'gallery.catalogue.delete');

        $galleryCatalogue = $this->galleryCatalogueRepository->getGalleryCatalogueById($id, $this->language);

        $config['seo'] = __('messages.galleryCatalogue');
        $config['method'] = 'delete';
        // dd($galleryCatalogue);
        $template = 'backend.gallery.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'galleryCatalogue',
            'config'
        ));
    }
    public function destroy($id, DeleteGalleryCatalogueRequest $request)
    {
        if ($this->galleryCatalogueRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('gallery.catalogue.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('gallery.catalogue.index');
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
