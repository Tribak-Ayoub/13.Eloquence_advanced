<?php

namespace Modules\PkgBlog\App\Imports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\PkgBlog\App\Models\Article;
use Modules\PkgBlog\App\Models\Category;
use Modules\PkgBlog\App\Models\Tag;

class ArticlesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $category = Category::firstOrCreate([
            'name' => $row['category'] ?? 'Uncategorized'
        ]);
        
        return new Article([
            'title' => $row['title'] ?? 'Untitled', 
            'content' => $row['content'] ?? 'No content',
            'user_id' => Auth::id() ?? 1,
            'category_id' => $category->id,
        ]);

        $article->save();

        // Attach Tags (comma-separated in CSV)
        if (!empty($row['tags'])) {
            $tagNames = explode(',', $row['tags']);
            $tagIds = [];
            
            foreach ($tagNames as $tagName) {
                $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }

            $article->tags()->sync($tagIds);
        }

        return $article;
    }
}
