<?php

namespace App\Providers;

use Illuminate\Support\Facades\View as ViewFace;
use Illuminate\Support\ServiceProvider;
use App\Repositories\LanguageRepository as LanguageRepository;
use Illuminate\View\View;
use App\Services\Interfaces\LanguageServiceInterface as languageService;
use Illuminate\Http\Request;


class LanguageComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $languageRepository;
    protected $languageService;

    public function __construct($app)
    {
        // parent::__construct($app);
    }
    public function register(): void
    {
        // You can register any bindings or dependencies here if needed
    }

    /**
     * Bootstrap services.
     */
    public function boot(Request $request): void
    {
        ViewFace::composer('backend.dashboard.layout', function (View $view) use ($request) {
            $languageService = app(languageService::class); // Lấy service từ container
            $languages = $languageService->paginate($request); // Lấy dữ liệu phân trang
            $view->with('languages', $languages);
        });
    }
}
