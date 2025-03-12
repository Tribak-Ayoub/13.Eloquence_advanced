<?php

namespace Modules\PkgBlog\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\PkgBlog\App\Models\Article;
use Modules\PkgBlog\App\Models\Tag;

class ArticleTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::all();
        foreach ($articles as $article) {
            $randomtag = Tag::inRandomOrder()->first();
            $article->tags()->attach($randomtag->id);
        }
    }
}
