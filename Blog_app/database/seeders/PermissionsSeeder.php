<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create article',
            'edit article',
            'delete article',
            'view article',
            'manage users',
            'edit settings',
        ];

        foreach($permissions as $permission){
            Permission::create(['name' => $permission]);
        }
    }
}
