<?php

namespace Modules\PkgBlog\App\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgBlog\App\Models\Article;

class ArticleService
{
    public function paginate($filters = [], $perPage = 10)
    {
        $query = Article::latest();

        // Apply category filter
        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        // Apply tag filter
        if (!empty($filters['tag'])) {
            $query->whereHas('tags', function ($query) use ($filters) {
                $query->where('tags.id', $filters['tag']);
            });
        }

        // Apply search filter
        if (!empty($filters['search'])) {
            $query->where(function ($query) use ($filters) {
                $query->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('content', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate($perPage);
    }

    public function getArticleById($id)
    {
        return Article::with(['category', 'tags', 'comments'])->findOrFail($id);
    }

    public function createArticle(array $data)
    {
        $article = Article::create([
            'title' => $data['title'],
            'category_id' => $data['category'],
            'content' => $data['content'],
            'user_id' => Auth::id(),
        ]);

        if (!empty($data['tags'])) {
            $article->tags()->attach($data['tags']);
        }

        return $article;
    }

    public function updateArticle(Article $article, array $data)
    {

        $article->update([
            'title' => $data['title'],
            'category_id' => $data['category'],
            'content' => $data['content'],
        ]);

        if (isset($data['tags'])) {
            $article->tags()->sync($data['tags']);
        }

        return $article;
    }

    public function deleteArticle(Article $article)
    {
        $article->delete();
        return true;
    }
}
