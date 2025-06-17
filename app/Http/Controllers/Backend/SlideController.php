<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\SlideServiceInterface as SlideService;
// use App\Services\Interfaces\SlideCatalogueServiceInterface as SlideCatalogueService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceService;
// use App\Repositories\Interfaces\SlideCatalogueRepositoryInterface as SlideCatalogueRepository;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\SaveChildrenSlideRequest;
use Illuminate\Http\Request;
use App\Http\Requests\SlideUpdateRequest;
use App\Repositories\SlideRepository as RepositoriesSlideRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use App\Helpers\MyHelper;


class SlideController extends Controller
{
    use AuthorizesRequests;
    protected $slideService;
    protected $provinceRepository;
    protected $slideRepository;
    protected $slideCatalogueRepository;
    protected $slideCatalogueService;

    public function __construct(SlideService $slideService, RepositoriesSlideRepository $slideRepository)
    {
        $this->slideService = $slideService;
        $this->slideRepository = $slideRepository;
        // $this->slideCatalogueRepository = $slideCatalogueRepository;
        // $this->slideCatalogueService = $slideCatalogueService;
    }

    public function index(Request $request)
    {


        $config = $this->config();
        $config['seo'] = __('messages.slide');
        $config['model'] = 'Slide';
        $slides = $this->slideService->paginate($request, $this->currentLanguage());
        $languageId = $this->currentLanguage();
        $template = 'backend.slide.slide.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'slides',
            'languageId',

        ));
    }




    public function create()
    {

        $config = $this->config();

        $config['seo'] = __('messages.slide');

        $config['method'] = 'create';
        $template = 'backend.slide.slide.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }




    public function store(StoreSlideRequest $storerequest)
    {
        if ($this->slideService->create($storerequest, $this->currentLanguage())) {
            flash()->success('them ban ghi thanh cong');
            return redirect()->route('slide.index');
        }

        return redirect()->route('slide.index')->with('error', 'Thêm mới bản ghi không thành công');
    }

    public function edit($id)
    {
        $slide = $this->slideRepository->findById($id);
        // $payload = $slide->toArray();

        $config = $this->config();
        $config['seo'] = __('messages.slide');
        $config['method'] = 'update';
        $template = 'backend.slide.slide.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'slide'
        ));
    }

    public function update(SlideUpdateRequest $request, $id)
    {
        // dd($request->all());
        if ($this->slideService->update($id, $request, $this->currentLanguage())) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('slide.index');
        }
        return redirect()->route('slide.index')->with('error', 'Cập nhật bản ghi không thành công');
    }

    public function delete($id)
    {
        $slideCatalogue = $this->slideCatalogueRepository->findById($id);
        $slide = $this->slideRepository->findById($id);
        $config = $this->config();

        $config['seo'] = __('messages.slide');
        $config['method'] = 'delete';


        $template = 'backend.slide.slide.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'slide',
            'config',
            'slideCatalogue'
        ));
    }
    public function destroy($id)
    {
        if ($this->slideService->destroy($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('slide.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('slide.index');
        }
    }
    private function config()
    {
        return [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/seo.js',
                '/backend/plugins/ckeditor/ckeditor.js',
                '/backend/library/slide.js',
                '/backend/js/plugins/nestable/jquery.nestable.js',

            ],
            'css' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ],
            'model' => 'Slide'
        ];
    }
}
