<?php

namespace Modules\Core\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // You can bind shared services here
    }

    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(module_path('Core', 'Routes/web.php'));

        // Load translations
        $this->loadTranslationsFrom(module_path('Core', 'Resources/lang'), 'core');

        // Load migrations
        $this->loadMigrationsFrom(module_path('Core', 'Database/Migrations'));
    }
}
