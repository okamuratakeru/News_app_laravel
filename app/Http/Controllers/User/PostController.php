<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostRequest;
use App\Services\CurrentUserService;
class PostController extends Controller
{
    private $post;
    private $category;
    private $currentUserService;

    public function __construct()
    {
        $this->post = new Post();
        $this->category = new Category();
        $this->currentUserService = new CurrentUserService();
    }
    /**
     * 投稿リスト
     * 
     * @param int $id ユーザーID
     * @return Response src/resources/views/user/list/index.blade.phpを表示
     */
    public function index(int $id)
    {
        $posts = $this->post->getAllPostsByUserId($id);
        
        return view('user.list.index', compact('posts'));
    }

    /**
     * 記事投稿画面
     */
    public function create()
    {
        $categories = $this->category->getAllCategories();
        return view('user.list.create', compact('categories'));
    }

    public function store(PostRequest $request)
    {
        $user_id = $this->currentUserService->getCurrentUserId();

        switch (true) {
            // 下書き保存クリック時の処理
            case $request->has('save_draft'):
                $this->post->insertPostToSaveDraft($user_id, $request);
                break;
            // 公開クリック時の処理
            case $request->has('release'):
                $this->post->insertPostToRelease($user_id, $request);
                break;
            // 予約公開クリック時の処理
            case $request->has('reservation_release'):
                $this->post->insertPostToReservationRelease($user_id, $request);
                break;
            // 上記以外の処理
            default:
                $this->post->insertPostToSaveDraft($user_id, $request);
                break;
        }


        return redirect()->route('user.index', ['id' => $user_id]);
    }

    /**
     * 記事詳細
     * 
     * @param int $post_id 投稿ID
     * @return Response src/resources/views/user/list/show.blade.phpを表示
     */
    public function show($post_id) {
        // リクエストされた投稿IDをもとにpostsテーブルから一意のデータを取得
        $showPostData = $this->post->feachPostDateByPostId($post_id);
        return view('user.list.show', compact(
            'showPostData',
        ));
    }

    /**
     * 記事編集
     * 
     * @param int $post_id 投稿ID
     * @return Response src/resources/views/user/list/edit.blade.phpを表示
     */
    public function edit($post_id) {
        $post = $this->post->fetchPostDataByPostId($post_id);
        $categories = $this->category->getAllCategories();
        return view('user.list.edit', compact(
            'post',
            'categories',
        ));
    }

    /**
     * 記事更新
     * 
     * @param int $post_id 投稿ID
     * @return Response src/resources/views/user/list/index.blade.phpを表示
     */
    public function update(PostRequest $request, $post_id) {
        $user_id = $this->currentUserService->getCurrentUserId();

        $post = $this->post->fetchPostDataByPostId($post_id);
        $publish_status = 0; // デフォルトは下書き
    
        if ($request->has('release')) {
            $publish_status = 1; // 公開
        } elseif ($request->has('reservation_release')) {
            $publish_status = 2; // 予約公開
        }
    
        $this->post->updatePostStatus($request, $post, $publish_status);
        return redirect()->route('user.index', ['id' => $user_id]);
    }

    /**
     * 記事論理削除
     * 
     * @param int $post_id 投稿ID
     * @return Response src/resources/views/user/list/index.blade.phpを表示
     */
    public function trash($post_id) {
        $user_id = $this->currentUserService->getCurrentUserId();
        $this->post->trashPost($post_id);
        return redirect()->route('user.index', ['id' => $user_id]);
    }


}
