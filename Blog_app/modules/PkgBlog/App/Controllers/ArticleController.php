<?php

namespace Modules\PkgBlog\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\Core\App\Controllers\BaseController;
use Modules\PkgBlog\App\Models\Article;
use Modules\PkgBlog\App\Models\Category;
use Modules\PkgBlog\App\Models\Comment;
use Modules\PkgBlog\App\Models\Tag;
use Modules\PkgBlog\App\Requests\StoreArticleRequest;
use Modules\PkgBlog\App\Services\ArticleService;

class ArticleController extends BaseController
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {   
        $filters = [
            'category' => $request->category,
            'tag' => $request->tag,
            'search' => $request->search,
        ];

        $articles = $this->articleService->paginate($filters);
        $ArticleCount = Article::count();
        $CommentCount = Comment::count();
        $UserCount = User::count();
        $categories = Category::all();
        $tags = Tag::all();

        if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))) {
            return view('blog::admin.article.index', compact('articles', 'categories', 'tags', 'ArticleCount', 'CommentCount', 'UserCount'));
        }

        return view('blog::public.index', compact('articles', 'categories', 'tags'));
    }

    public function create()
    {
        $this->authorize('create', Article::class);

        $categories = Category::all();
        $allTags = Tag::all();

        return view('blog::admin.article.create', compact('categories', 'allTags'));
    }

    public function store(StoreArticleRequest $request)
    {
        $this->authorize('create', Article::class);

        $validated = $request->validated();
        $this->articleService->createArticle($validated);

        return redirect()->route('blog.articles.index')->with('success', 'L\'article a bien été créé');
    }

    public function show(string $id)
    {
        $article = $this->articleService->getArticleById($id);
        $commentableId = $article->id;
        $commentableType = Article::class;

        if (Auth::check() && !(Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))) {
            return view('blog::admin.article.show', compact('article', 'commentableId', 'commentableType'));
        }

        return view('blog::public.show', compact('article', 'commentableId', 'commentableType'));
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);
        $categories = Category::all();
        $allTags = Tag::all();
        $selectedTags = $article->tags->pluck('id')->toArray();

        return view('blog::admin.article.edit', compact('article', 'categories', 'allTags', 'selectedTags'));
    }

    public function update(StoreArticleRequest $request, $id)
    {
        $validated = $request->validated();
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);
        $this->articleService->updateArticle($article, $validated);

        return redirect()->route('blog.articles.index')->with('success', 'L\'article a bien été modifié');
    }

    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);
        $this->authorize('delete', $article);
        $this->articleService->deleteArticle($article);

        return redirect()->route('blog.articles.index')->with('success', 'L\'article a bien été supprimé');
    }
}
