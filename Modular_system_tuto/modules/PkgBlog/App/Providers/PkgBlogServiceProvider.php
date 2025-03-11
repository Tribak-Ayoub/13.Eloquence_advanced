<?php

namespace Modules\PkgBlog\App\Providers;

use Illuminate\Support\ServiceProvider;

class PkgBlogServiceProvider extends ServiceProvider
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
         // Load migrations
         $this->loadMigrationsFrom(__DIR__ . '/../../Database/Migrations');

         // Load all route files in the Routes directory
         foreach (glob(__DIR__ . '/../../Routes/*.php') as $routeFile) {
             $this->loadRoutesFrom($routeFile);
         }
 
         // Load views
         $this->loadViewsFrom(__DIR__ . '/../../Resources/Views', 'PkgBlog');
    }
}
