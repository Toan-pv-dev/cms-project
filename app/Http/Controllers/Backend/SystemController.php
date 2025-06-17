<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Classes\System;
use App\Repositories\SystemRepository as SystemRepository;
use App\Services\Interfaces\SystemServiceInterface as SystemService;
use App\Models\Language;
use Illuminate\Support\Facades\Session;

class SystemController extends Controller
{
    protected $systemData;
    protected $systemService;
    protected $systemRepository;
    use AuthorizesRequests;

    public function __construct(System $systemData, SystemRepository $systemRepository, SystemService $systemService)
    {
        $this->systemData = $systemData;
        $this->systemService = $systemService;
        $this->systemRepository = $systemRepository;
    }


    public function index(Request $request)
    {
        $languageId = $request->input('language_id', 1);
        $localeCode = Session::get('app_locale', config('app.locale'));
        $language = Language::where('canonical', $localeCode)->first();

        $languageId = $language->id;
        $allSystems = $this->systemRepository->getByLanguageId($languageId);
        $configData = $this->systemData->configData();
        $systems = $allSystems->mapWithKeys(function ($item) {
            return [$item->keyword => $item->content];
        })->toArray();

        // Lấy language_id và user_id từ 1 item bất kỳ trong collection (ví dụ item đầu tiên)
        $first = $allSystems->first();

        $systems['language_id'] = $first->language_id ?? null;
        $systems['user_id'] = $first->user_id ?? null;

        $config = [
            'js' => [
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/finder.js',
                '/backend/plugins/ckeditor/ckeditor.js',


            ],
        ];
        $config['seo'] = __('messages.system');
        $template = 'backend.system.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'configData',
            'systems'
        ));
    }
    public function store(Request $request)
    {
        $languageId = $request->input('language_id', 1);


        if ($this->systemService->create($request, $languageId)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('system.index');
        }
        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('system.index');
    }

    public function translate($languageId = 0)
    {
        $configData = $this->systemData->configData();
        $allSystems = $this->systemRepository->getByLanguageId($languageId);
        $systems = $allSystems->mapWithKeys(function ($item) {
            return [$item->keyword => $item->content];
        })->toArray();

        $first = $allSystems->first();

        $systems['language_id'] = $first->language_id ?? null;
        $systems['user_id'] = $first->user_id ?? null;
        $config = [
            'js' => [
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/finder.js',
                '/backend/plugins/ckeditor/ckeditor.js',


            ],
        ];
        $config['seo'] = __('messages.system');
        $config['method'] = 'translate';
        $template = 'backend.system.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'configData',
            'systems',
            'languageId'
        ));
    }

    public function saveTranslate(Request $request, $languageId = 0)
    {
        if ($this->systemService->create($request, $languageId)) {
            flash()->success('Cập nhật bản ghi thành công');
            return redirect()->route('system.translate', ['languageId' => $languageId]);
        }
        flash()->error('Cập nhật bản ghi không thành công');
        return redirect()->route('system.translate', ['languageId' => $languageId]);
    }
}
