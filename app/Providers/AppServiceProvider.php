<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface;
use App\Repositories\UserCatalogueRepository;
use App\Services\UserCatalogueService;

use App\Services\Interfaces\LanguageServiceInterface;
use App\Repositories\Interfaces\LanguageRepositoryInterface;
use App\Repositories\LanguageRepository;
use App\Services\LanguageService;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface;
use App\Repositories\PostCatalogueRepository;
use App\Services\PostCatalogueService;


use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Repositories\Interfaces\UserRepositoryInterface;


use App\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Repositories\ProvinceRepository;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\DistrictRepository;
use App\Repositories\WardRepository;
use App\Repositories\Interfaces\WardRepositoryInterface;




class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserCatalogueServiceInterface::class, UserCatalogueService::class);
        $this->app->bind(UserCatalogueRepositoryInterface::class, UserCatalogueRepository::class);
        $this->app->bind(WardRepositoryInterface::class, WardRepository::class);
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(LanguageServiceInterface::class, LanguageService::class);
        $this->app->bind(LanguageRepositoryInterface::class, LanguageRepository::class);

        $this->app->bind(PostCatalogueServiceInterface::class, PostCatalogueService::class);
        $this->app->bind(PostCatalogueRepositoryInterface::class, PostCatalogueRepository::class);
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}