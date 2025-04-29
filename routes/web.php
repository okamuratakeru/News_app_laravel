<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\User\PostController;
use App\Http\Controllers\User\TrashController;
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

    // 投稿編集
    Route::get('/post/edit/{post_id}', [PostController::class, 'edit'])
        ->name('post.edit');
    
    // 投稿更新
    Route::post('/post/update/{post_id}', [PostController::class, 'update'])
        ->name('post.update');

    // 投稿論理削除
    Route::post('/post/trash/{post_id}', [PostController::class, 'trash'])
        ->name('post.trash');

    // ゴミ箱一覧
    Route::get('/user/{id}/trash/index', [TrashController::class, 'index'])
        ->name('trash.index');

    // 記事復元
    Route::post('/post/reconstruction/{post_id}', [TrashController::class, 'reconstruction'])
        ->name('post.reconstruction');

    // 記事完全削除
    Route::post('/post/delete/{post_id}', [TrashController::class, 'delete'])
        ->name('post.delete');
});

require __DIR__.'/auth.php';
