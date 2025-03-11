<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\PkgBlog\Database\Seeders\ArticleSeeder;
use Modules\PkgBlog\Database\Seeders\ArticleTagSeeder;
use Modules\PkgBlog\Database\Seeders\CategorySeeder;
use Modules\PkgBlog\Database\Seeders\TagSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            ArticleSeeder::class,
            ArticleTagSeeder::class,
        ]);

        // Run module-specific seeders
        $this->runModuleSeeders();
    }

    /**
     * Dynamically loads and runs all module seeders.
     */
    protected function runModuleSeeders(): void
    {
        $modulesPath = base_path('modules');
        $modules = File::directories($modulesPath);

        foreach ($modules as $module) {
            if (basename($module) === 'Core') {
                continue;
            }

            $seederDirectory = $module . '/Database/Seeders';

            if (!File::exists($seederDirectory)) {
                continue;
            }

            $seederFiles = File::files($seederDirectory);

            foreach ($seederFiles as $file) {
                $seederClass = 'Modules\\' . Str::studly(basename($module)) . '\\Database\\Seeders\\' . pathinfo($file, PATHINFO_FILENAME);

                if (class_exists($seederClass)) {
                    $this->call($seederClass);
                }
            }
        }
    }
}