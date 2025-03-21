<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // $this->registerPolicies();

        Gate::define('modules', function ($user, $permissionName) {

            if ($user->publish == 0) return false;

            $permission = $user->userCatalogue->permissions;

            if ($permission->contains('canonical', $permissionName)) return true;

            return false;

            // Kiểm tra giá trị truyền vào
        });
    }

    /**
     * Register any authentication / authorization policies.
     */
    protected function registerPolicies(): void
    {
        // Register the policies here
    }
}
