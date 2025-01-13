<?php

namespace App\Providers;

use Illuminate\Support\Facades\View as ViewFace;
use Illuminate\Support\ServiceProvider;
use App\Repositories\LanguageRepository as LanguageRepository;
use Illuminate\View\View;

class LanguageComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $languageRepository;


    public function __construct($app)
    {
        // parent::__construct($app);
        $this->languageRepository = $app->make(LanguageRepository::class);
    }
    public function register(): void
    {
        // You can register any bindings or dependencies here if needed
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Use View::share for global variables or View::composer for specific views
        ViewFace::composer('backend.dashboard.components.navbar', function (View $view) {
            $language = $this->languageRepository->all(); // Example language value
            $view->with('language', $language); // Pass the variable to the view
        });
    }
}
