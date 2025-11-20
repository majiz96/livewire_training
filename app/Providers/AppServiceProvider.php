<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Can enter to admin page
        Gate::define('admin-access', function ($user) {
            return $user->position && $user->position->access >= 1;
        });

        //Can change some data in product table
        Gate::define('edit-access', function ($user) {
            return $user->position && $user->position->access >= 2;
        });

        //Can change all data's in product table and some data's in other pages
        Gate::define('operation-access', function ($user) {
            return $user->position && $user->position->access >= 3;
        });

        //Can use whole app options and controls
        Gate::define('full-access', function ($user) {
            return $user->position && $user->position->access == 4;
        });
    }
}
