<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\User\PostController;
use Illuminate\Support\Facades\Route;


Route::get('/', [TopController::class, 'top'])->name('top');


Route::middleware('auth')->group(function () {
    Route::get('/user/{id}/index', [PostController::class, 'index'])
        ->name('user.index');
    
    Route::get('/post/create', [PostController::class, 'create'])
        ->name('post.create');
    
    // 投稿登録処理
    Route::post('/post/store', [PostController::class, 'store'])
        ->name('post.store');
});

require __DIR__.'/auth.php';
