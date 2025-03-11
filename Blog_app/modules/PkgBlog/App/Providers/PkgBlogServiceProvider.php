<?php

namespace Modules\PkgBlog\App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\PkgBlog\App\Policies\ArticlePolicy;
use Modules\PkgBlog\Models\Article;

class PkgBlogServiceProvider extends ServiceProvider
{
    protected $policies = [
        Article::class => ArticlePolicy::class,
    ];
    
    public function register(): void
    {
        // Register module-specific bindings here (if needed)
    }

    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(function ($modelName) {
            return str_replace('Modules\\PkgBlog\\Models', 'Modules\\PkgBlog\\Database\\Factories', $modelName) . 'Factory';
        });
        
        // Load routes
        $this->loadRoutesFrom(module_path('PkgBlog', 'Routes/web.php'));

        // Load views
        $this->loadViewsFrom(module_path('PkgBlog', 'Resources/views'), 'pkgblog');

        // Load migrations
        $this->loadMigrationsFrom(module_path('PkgBlog', 'Database/Migrations'));
    }
}
