<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Services\CurrentUserService;
use Illuminate\Support\Facades\Auth;


class TrashController extends Controller
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

    public function index()
    {
        $user_id = $this->currentUserService->getCurrentUserId();
        $posts = $this->post->getAllTrashPostsByUserId($user_id);
        return view('user.trash.index', compact('posts'));
    }

    public function reconstruction($post_id)
    {
        $user_id = $this->currentUserService->getCurrentUserId();
        $this->post->reconstructionPost($post_id);
        return redirect()->route('trash.index', ['id' => $user_id]);
    }

    public function delete($post_id) {
        $user_id = $this->currentUserService->getCurrentUserId();
        
        // 投稿を取得して完全に削除
        $post = $this->post->find($post_id);
        if ($post) {
            $post->delete();
        }

        return redirect()->route('trash.index', ['id' => $user_id]);
    }
}
