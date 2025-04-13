<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $post;

    public function __construct()
    {
        $this->post = new Post();
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
}
