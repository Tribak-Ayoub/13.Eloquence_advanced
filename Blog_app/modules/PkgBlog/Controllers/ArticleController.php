<?php

namespace Modules\PkgBlog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\Core\Controllers\BaseController;
use Modules\PkgBlog\App\Requests\StoreArticleRequest;
use Modules\PkgBlog\Models\Article;
use Modules\PkgBlog\Models\Category;
use Modules\PkgBlog\Models\Comment;
use Modules\PkgBlog\Models\Tag;
use Modules\PkgBlog\Services\ArticleService;
// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArticleController extends BaseController
{
  // use AuthorizesRequests;
  protected $articleService;

  public function __construct(ArticleService $articleService)
  {
    $this->articleService = $articleService;
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


    if ($this->authorize('viewAny', Article::class)) {
      return view('admin.article.index', compact('articles', 'categories', 'tags', 'ArticleCount', 'CommentCount', 'UserCount'));
    }

    return view('public.index', compact('articles', 'categories', 'tags'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $this->authorize('create', Article::class);

    $categories = Category::all();
    $allTags = Tag::all();

    return view('admin.article.create', compact('categories', 'allTags'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreArticleRequest $request)
  {
    $this->authorize('create', Article::class);

    $validated = $request->validated();

    $article = $this->articleService->createArticle($validated);

    return redirect()->route('articles.index')->with('success', 'L\'article a bien été créé');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $article = $this->articleService->getArticleById($id);
    $commentableId = $article->id;
    $commentableType = Article::class; // This is directly calculated in the controller.

    if (Auth::check() && !(Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))) {
      return view('admin.article.show', compact('article', 'commentableId', 'commentableType'));
    } else {
      return view('public.show', compact('article', 'commentableId', 'commentableType'));
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $article = Article::findOrFail($id);
    $this->authorize('update', $article);
    $categories = Category::all();
    $allTags = Tag::all();
    $selectedTags = $article->tags->pluck('id')->toArray();

    return view('admin.article.edit', compact('article', 'categories', 'allTags', 'selectedTags'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(StoreArticleRequest $request, $id)
  {
    $validated = $request->validated();
    $article = Article::findOrFail($id);
    $this->authorize('update', $article);
    $article = $this->articleService->updateArticle($article, $validated);

    return redirect()->route('articles.index')->with('success', 'L\'article a bien été modifié');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $article = Article::findOrFail($id);
    $this->authorize('delete', $article);

    $article = $this->articleService->deleteArticle($article);
    
    return redirect()->route('articles.index')->with('success', 'L\'article a bien été supprimé');
  }
}
