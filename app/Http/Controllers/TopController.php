<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Category;
use App\Services\CurrentUserService;
class TopController extends Controller
{
    protected $post;
    protected $category;
    protected $currentUserService;
    public function __construct()
    {
        $this->post = new Post();
        $this->category = new Category();
        $this->currentUserService = new CurrentUserService();
    }

    /**
     * トップページ
     * 
     * @return Response src/resources/views/top.blade.phpを表示
     */
    public function top()
    {
        $user_id = $this->currentUserService->getCurrentUserId();

        // カテゴリーを取得
        $categories = $this->category->getAllCategories();
        // 投稿を取得
        $posts = $this->post->getAllPostsByUserId($user_id);

        return view('top', compact(
            'user_id',
            'categories',
            'posts',
        ));
    }

    /**
     * 記事詳細
     * 
     * @param int $post_id 記事ID
     * @return Response src/resources/views/article/show.blade.php
     */
    public function articleShow($post_id)
    {
        $user_id = $this->currentUserService->getCurrentUserId();
        
        // カテゴリーを全て取得
        $categories = $this->category->getAllCategories();
        // 記事IDをもとに特定の記事のデータを取得
        $post = $this->post->feachPostDateByPostId($post_id);
        return view('article.show', compact(
            'user_id',
            'categories',
            'post',
        ));
    }

    /**
     * カテゴリー別記事一覧
     * 
     * @param int $category_id カテゴリーID
     * @return Response src/resources/views/article/category.blade.php
     */
    public function articleCategory($category_id)
    {
        $user_id = $this->currentUserService->getCurrentUserId();

        $posts = $this->post->getPostsByCategoryId($category_id);
        $categories = $this->category->getAllCategories();
        return view('article.category', compact(
            'user_id',
            'posts',
            'categories',
        ));
    }
}
