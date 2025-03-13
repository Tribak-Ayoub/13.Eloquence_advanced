<?php

namespace Modules\PkgBlog\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\PkgBlog\App\Models\Article;
use Modules\PkgBlog\App\Policies\ArticlePolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

class PkgBlogServiceProvider extends ServiceProvider
{
    protected $policies = [
        Article::class => ArticlePolicy::class,
    ];

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
        // Manually register the factory path
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $modelName = str_replace('Modules\\PkgBlog\\App\\Models', 'Modules\\PkgBlog\\Database\\Factories', $modelName);
            return $modelName . 'Factory';
        });

        $this->loadMigrationsFrom(__DIR__ . '/../../Database/Migrations');

        foreach (glob(__DIR__ . '/../../Routes/*.php') as $routeFile) {
            $this->loadRoutesFrom($routeFile);
        }

        $this->loadViewsFrom(__DIR__ . '/../../Resources/Views', 'blog');
    }
}
