<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        $this->call([
            PermissionsSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            ArticleSeeder::class,
            ArticleTagSeeder::class,
        ]);

        $this->runModuleSeeders();
    }

    protected function runModuleSeeders(): void
    {
        $modulesPath = base_path('modules');
        $modules = File::directories($modulesPath);

        foreach ($modules as $module) {
            if (basename($module) === 'Core') {
                continue;
            }

            $seederFile = $module . '/Database/Seeders/' . Str::studly(basename($module)) . 'Seeder.php';

            if (File::exists($seederFile)) {
                $seederClass = 'Modules\\' . Str::studly(basename($module)) . '\\Database\\Seeders\\' . Str::studly(basename($module)) . 'Seeder';
                $this->call($seederClass);
            }
        }
    }
}
