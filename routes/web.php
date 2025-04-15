<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\User\PostController;
use Illuminate\Support\Facades\Route;


Route::get('/', [TopController::class, 'top'])->name('top');

// 総合トップ記事詳細画面
Route::get('/article/{post_id}', [TopController::class, 'articleShow'])
    ->name('top.article.show');

// カテゴリー別記事一覧画面
Route::get('/article/category/{category_id}', [TopController::class, 'articleCategory'])
    ->name('top.article.category');

Route::middleware('auth')->group(function () {
    Route::get('/user/{id}/index', [PostController::class, 'index'])
        ->name('user.index');
    
    Route::get('/post/create', [PostController::class, 'create'])
        ->name('post.create');
    
    // 投稿登録処理
    Route::post('/post/store', [PostController::class, 'store'])
        ->name('post.store');

    // 投稿詳細
    Route::get('/post/show/{post_id}', [PostController::class, 'show'])
        ->name('post.show');
});

require __DIR__.'/auth.php';
