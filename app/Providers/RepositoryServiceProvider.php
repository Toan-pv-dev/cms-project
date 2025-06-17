<?php

namespace App\Providers;

use App\Repositories\Interfaces\AttributeRepositoryInterface;
use App\Repositories\AttributeRepository;

use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;
use App\Repositories\AttributeCatalogueRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface;
use App\Repositories\ProductCatalogueRepository;


use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\{
    UserCatalogueRepositoryInterface,
    UserRepositoryInterface,
    LanguageRepositoryInterface,
    PostCatalogueRepositoryInterface,
    PostRepositoryInterface,
    PermissionRepositoryInterface,
    GenerateRepositoryInterface,
    RouterRepositoryInterface,
    WardRepositoryInterface,
    DistrictRepositoryInterface,
    ProvinceRepositoryInterface,
    ProductVariantRepositoryInterface,
    ProductVariantLanguageRepositoryInterface,
    ProductVariantAttributeRepositoryInterface,
    SystemRepositoryInterface,
    MenuRepositoryInterface,
    MenuCatalogueRepositoryInterface,
    SlideRepositoryInterface,
    WidgetRepositoryInterface
};

use App\Repositories\{
    UserCatalogueRepository,
    UserRepository,
    LanguageRepository,
    PostCatalogueRepository,
    PostRepository,
    PermissionRepository,
    GenerateRepository,
    RouterRepository,
    WardRepository,
    DistrictRepository,
    ProvinceRepository,
    ProductVariantRepository,
    ProductVariantLanguageRepository,
    ProductVariantAttributeRepository,
    SystemRepository,
    MenuRepository,
    MenuCatalogueRepository,
    SlideRepository,
    WidgetRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerBindings();
    }

    protected function registerBindings(): void
    {




        $this->app->bind(UserCatalogueRepositoryInterface::class, UserCatalogueRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(LanguageRepositoryInterface::class, LanguageRepository::class);
        $this->app->bind(PostCatalogueRepositoryInterface::class, PostCatalogueRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(GenerateRepositoryInterface::class, GenerateRepository::class);
        $this->app->bind(RouterRepositoryInterface::class, RouterRepository::class);
        $this->app->bind(WardRepositoryInterface::class, WardRepository::class);
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->bind(RouterRepositoryInterface::class, RouterRepository::class);
        $this->app->bind(ProductCatalogueRepositoryInterface::class, ProductCatalogueRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(AttributeCatalogueRepositoryInterface::class, AttributeCatalogueRepository::class);
        $this->app->bind(AttributeRepositoryInterface::class, AttributeRepository::class);
        $this->app->bind(ProductVariantRepositoryInterface::class, ProductVariantRepository::class);
        $this->app->bind(ProductVariantLanguageRepositoryInterface::class, ProductVariantLanguageRepository::class);
        $this->app->bind(ProductVariantAttributeRepositoryInterface::class, ProductVariantAttributeRepository::class);
        $this->app->bind(SystemRepositoryInterface::class, SystemRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(MenuCatalogueRepositoryInterface::class, MenuCatalogueRepository::class);
        $this->app->bind(SlideRepositoryInterface::class, SlideRepository::class);
        $this->app->bind(WidgetRepositoryInterface::class, WidgetRepository::class);
    }
}
