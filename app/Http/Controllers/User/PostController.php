<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostRequest;
class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct()
    {
        $this->post = new Post();
        $this->category = new Category();
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
        $user = Auth::user();
        $user_id = $user->id;

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
}
