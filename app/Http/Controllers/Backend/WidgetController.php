<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceService;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use App\Http\Requests\StoreWidgetRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateWidgetRequest;
use App\Repositories\WidgetRepository as RepositoriesWidgetRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;


class WidgetController extends Controller
{
    use AuthorizesRequests;
    protected $widgetService;
    protected $provinceRepository;
    protected $widgetRepository;

    public function __construct(WidgetService $widgetService,  RepositoriesWidgetRepository $widgetRepository)
    {
        $this->widgetService = $widgetService;
        $this->widgetRepository = $widgetRepository;
    }

    public function index(Request $request)
    {

        $config = $this->config();
        $config['seo'] =  __('messages.widget');
        $config['model'] = 'widget';
        $widgets = $this->widgetService->paginate($request);
        // dd($widgets);
        // dd($widgets);
        $template = 'backend.widget.widget.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'widgets',

        ));
    }


    public function create()
    {
        $config = $this->config();
        // dd($location);
        // $config = $this->configData();
        $config['seo'] = __('messages.widget');
        $config['method'] = 'create';
        $template = 'backend.widget.widget.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }
    public function store(StoreWidgetRequest $storerequest)
    {
        // dd($storerequest);
        if ($this->widgetService->create($storerequest)) {
            flash()->success('them ban ghi thanh cong');
            return redirect()->route('widget.index');
        }

        return redirect()->route('widget.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function edit($id)
    {
        $widget = $this->widgetRepository->findById($id);
        $provinces = $this->provinceRepository->all();
        $config = $this->configData();
        $config['seo'] = config('apps.widget');
        $config['method'] = 'update';

        $template = 'backend.widget.widget.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'provinces',
            'widget'
        ));
    }
    public function update(UpdateWidgetRequest $updaterequest, $id)
    {
        // dd($updaterequest);
        if ($this->widgetService->update($id, $updaterequest)) {
            flash()->success('them ban ghi thanh cong');
            return redirect()->route('widget.index');
        }

        return redirect()->route('widget.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $widget = $this->widgetRepository->findById($id);

        $config['seo'] = config('apps.widget');
        $config['method'] = 'delete';
        // $config['method'] = 'delete';


        $template = 'backend.widget.widget.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'widget',
            'config'
        ));
    }
    public function destroy($id)
    {
        if ($this->widgetRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('widget.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('widget.index');
        }
    }
    private function config()
    {
        return  [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/finder.js',
                '/backend/library/seo.js',
                '/backend/library/widget.js',
                '/backend/plugins/ckeditor/ckeditor.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ]
        ];
    }
}
