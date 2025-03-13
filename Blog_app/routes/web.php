<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;

// Ensure Auth routes are inside 'web' middleware
// Route::middleware(['web'])->group(function () {
  
    Require base_path('Modules/PkgBlog/Routes/web.php');
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// });
