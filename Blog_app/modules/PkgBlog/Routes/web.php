<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgBlog\App\Controllers\ArticleController;
use Modules\PkgBlog\App\Controllers\CommentController;
use Modules\PkgBlog\App\Controllers\TagController;
use Modules\PkgBlog\App\Controllers\CategoryController;

Route::prefix('blog')->group(function () {

    // Public routes
    Route::get('/', [ArticleController::class, 'index'])->name('blog.public.index');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('blog.public.show');

    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::prefix('articles')->group(function () {
            Route::get('/', [ArticleController::class, 'index'])->name('blog.articles.index');
            Route::get('/create', [ArticleController::class, 'create'])->name('blog.articles.create');
            Route::post('/store', [ArticleController::class, 'store'])->name('blog.articles.store');
            Route::get('/{article}', [ArticleController::class, 'show'])->name('blog.articles.show');
            Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('blog.articles.edit');
            Route::put('/{article}', [ArticleController::class, 'update'])->name('blog.articles.update');
            Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('blog.articles.destroy');
        });

        Route::post('/comments/store', [CommentController::class, 'store'])->name('blog.comments.store');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('blog.comments.destroy');
    });

    // Admin routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::prefix('tags')->group(function () {
            Route::get('/', [TagController::class, 'index'])->name('blog.tags.index');
            Route::get('/create', [TagController::class, 'create'])->name('blog.tags.create');
            Route::post('/store', [TagController::class, 'store'])->name('blog.tags.store');
            Route::delete('/{tag}', [TagController::class, 'destroy'])->name('blog.tags.destroy');
        });

        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('blog.categories.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('blog.categories.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('blog.categories.store');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('blog.categories.destroy');
        });
    });
});

