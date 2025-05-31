<?php

namespace App\Providers;

use App\Services\Interfaces\AttributeServiceInterface;
use App\Services\AttributeService;

use App\Services\Interfaces\AttributeCatalogueServiceInterface;
use App\Services\AttributeCatalogueService;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\ProductService;

use App\Services\Interfaces\ProductCatalogueServiceInterface;
use App\Services\ProductCatalogueService;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use App\Services\Interfaces\{
    UserCatalogueServiceInterface,
    UserServiceInterface,
    LanguageServiceInterface,
    PostCatalogueServiceInterface,
    PostServiceInterface,
    PermissionServiceInterface,
    GenerateServiceInterface,
};
use App\Services\{
    UserCatalogueService,
    UserService,
    LanguageService,
    PostCatalogueService,
    PostService,
    PermissionService,
    GenerateService,
};

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerBindings();
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }

    protected function registerBindings(): void
    {


        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserCatalogueServiceInterface::class, UserCatalogueService::class);
        $this->app->bind(LanguageServiceInterface::class, LanguageService::class);
        $this->app->bind(LanguageServiceInterface::class, LanguageService::class);
        $this->app->bind(PostCatalogueServiceInterface::class, PostCatalogueService::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
        $this->app->bind(GenerateServiceInterface::class, GenerateService::class);
        $this->app->bind(ProductCatalogueServiceInterface::class, ProductCatalogueService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(AttributeCatalogueServiceInterface::class, AttributeCatalogueService::class);
        $this->app->bind(AttributeServiceInterface::class, AttributeService::class);
    }
}