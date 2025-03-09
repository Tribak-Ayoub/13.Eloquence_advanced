<?php

use Illuminate\Support\Facades\Route;

Route::prefix('core')->group(function () {
    Route::get('/test', function () {
        return 'Core Module is working!';
    });
});
