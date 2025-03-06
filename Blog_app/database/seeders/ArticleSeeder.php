<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'), 
        ]);

        Article::factory()->count(20)->create(['user_id' => $user->id]);
    }
}
