<?php

namespace Modules\PkgBlog\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PkgBlogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register module-specific bindings here (if needed)
    }

    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(module_path('PkgBlog', 'Routes/web.php'));

        // Load views
        $this->loadViewsFrom(module_path('PkgBlog', 'Resources/views'), 'pkgblog');

        // Load migrations
        $this->loadMigrationsFrom(module_path('PkgBlog', 'Database/Migrations'));
    }
}
