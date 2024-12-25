<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //$articles = \App\Models\Article::paginate(10);
    // if(Auth::check() && Auth::user()->role == 'admin'){
    //   
    //   return view('admin.index', compact('articles'));
    // }else{
    //   return view('public.index');
    // }
    $articles = Article::paginate(5);
    // return view('admin.index', compact('articles'));
    // $articles = Article::with(['category', 'tags', 'user'])->paginate(5);
    return view ('public.index', compact('articles'));
    
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
    $article= Article::where('id' , $id);
    $article->delete();
    return redirect()->route('articles.index')->with('success', 'L\'article a bien été supprimé');

  }
}
