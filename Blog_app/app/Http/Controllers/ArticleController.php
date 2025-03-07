<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\User;
use App\Services\ArticleService;

class ArticleController extends Controller
{
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


    if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))) {
      return view('admin.article.index', compact('articles', 'categories', 'tags', 'ArticleCount', 'CommentCount', 'UserCount'));
    }

    return view('public.index', compact('articles', 'categories', 'tags'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    if (!Auth::check() || !(Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))) {
      return redirect()->route('articles.index');
    }

    $categories = Category::all();
    $allTags = Tag::all();

    return view('admin.article.create', compact('categories', 'allTags'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreArticleRequest $request)
  {
    if (!Auth::check() && !(Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))) {
      return redirect()->route('articles.index');
    }

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
    // if (!Auth::check() || !(Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))) {
    //   return redirect()->route('articles.index');
    // }

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
    if (!Auth::check() || !(Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))) {
      return redirect()->route('articles.index');
    }
    $validated = $request->validated();

    // $validated = $request->validate([
    //   'title' => 'required|string|max:255',
    //   'category' => 'required|exists:categories,id',
    //   'content' => 'required|string',
    //   'tags' => 'array',
    //   'tags.*' => 'exists:tags,id',
    // ]);

    $article = Article::findOrFail($id);
    $this->authorize('update', $article);
    $article->update([
      'title' => $validated['title'],
      'category_id' => $validated['category'],
      'content' => $validated['content'],
    ]);

    $article->tags()->sync($validated['tags'] ?? []);

    return redirect()->route('articles.index')->with('success', 'L\'article a bien été modifié');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    if (!Auth::check() || !Auth::user()->hasRole('admin')) {
      return redirect()->route('articles.index');
    }

    $article = Article::where('id', $id);
    $article->delete();
    return redirect()->route('articles.index')->with('success', 'L\'article a bien été supprimé');
  }
}
