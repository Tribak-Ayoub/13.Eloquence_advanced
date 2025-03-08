<?php

namespace App\Providers;

use App\Models\Article;
use App\Policies\ArticlePolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Article::class => ArticlePolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadModuleServiceProviders();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrap();
    }

    protected function loadModuleServiceProviders()
    {
        $moduleProvidersPath = base_path('modules');
        $providerFiles = glob($moduleProvidersPath . '/*/App/Providers/*ServiceProvider.php');

        foreach ($providerFiles as $providerFile) {
            $providerClass = $this->getProviderClass($providerFile);
            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }

    protected function getProviderClass(string $file): string
    {
        $relativePath = str_replace(base_path(), '', $file);
        $relativePath = str_replace('/', '\\', $relativePath);
        $relativePath = trim($relativePath, '\\');
        $relativePath = str_replace('.php', '', $relativePath);

        if (substr($relativePath, 0, 7) === 'modules') {
            $relativePath = 'Modules' . substr($relativePath, 7);
        }

        return $relativePath;
    }
}
