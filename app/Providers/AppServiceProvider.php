<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\Facades\View;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsService::class, function () {
            return new SettingsService(base_path('settings.json'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with(['settings' => app(SettingsService::class)]);
        });
        Gate::define('access-admin-panel', function (User $user) {
            return $user->role === Role::ADMIN;
        });
        Gate::define('settings.view', function (User $user) {
            return $user->role === Role::ADMIN;
        });

        Gate::define('settings.update', function (User $user) {
            return $user->role === Role::ADMIN;
        });
    }
}
