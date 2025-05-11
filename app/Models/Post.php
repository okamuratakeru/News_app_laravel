<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'body',
        'publish_flg',
        'view_counter',
        'favorite_counter',
        'delete_flg',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reservation_posts()
    {
        return $this->hasMany(ReservationPost::class);
    }

    /**
     * ユーザーIDに紐づいた投稿リストを全て取得する
     * 
     * @param int $user_id ユーザーID
     * @return Post
     */
    public function getAllPostsByUserId($user_id)
    {
        $result = $this->where('user_id', $user_id)
                ->orderBy('updated_at', 'DESC')
                ->with('category')
                ->where('delete_flg', 0)
                ->get();
        return $result;
    }

    /**
     * 投稿データを全て取得し、最新更新日時順にソート。総合トップ画面に表示する記事はステータス「公開」(publish_flg=1)のみ
     */
    public function getPostsSortByLatestUpdate()
    {
        $result = $this->where('publish_flg', 1)
                ->orderBy('updated_at', 'DESC')
                ->with('user')
                ->with('category')
                ->get();
        return $result;
    }

    /**
     * 投稿IDをもとにpostsテーブルから一意の投稿データを取得
     * 
     * @param int $post_id 投稿ID
     * @return object $result App\Models\Post
     */
    public function feachPostDateByPostId($post_id)
    {
        $result = $this->find($post_id);
        return $result;
    }

    /**
     * カテゴリーIDに紐づいた投稿リストを全て取得する
     * 
     * @param int $category_id カテゴリーID
     * @return Post
     */
    public function getPostsByCategoryId($category_id)
    {
        $result = $this->where('category_id', $category_id)
                ->orderBy('updated_at', 'DESC')
                ->with('user')
                ->get();
        return $result;
    }

    /**
     * 下書き保存=>publish_flg=0
     * リクエストされたデータをpostsテーブルにinsertする
     * 
     * @param int $user_id ログインユーザーID
     * @param array $request リクエストデータ
     * @return object $result App\Models\Post
     */
    public function insertPostToSaveDraft($user_id, $request)
    {
        $result = $this->create([
            'user_id'          => $user_id,
            'category_id'      => $request->category,
            'title'            => $request->title,
            'body'             => $request->body,
            'publish_flg'      => 0,
            'view_counter'     => 0,
            'favorite_counter' => 0,
            'delete_flg'       => 0,
        ]);
        return $result;
    }

    /**
     * 既存の投稿データを更新する
     * 
     * @param int $post_id 投稿ID
     * @param array $request リクエストデータ
     * @return object $result App\Models\Post
     */
    public function updatePost($post_id, $request)
    {
        $result = $this->find($post_id);
        $result->update($request->all());
        return $result;
    }
    
    /**
     * 公開=>publish_flg=1
     * リクエストされたデータをpostsテーブルにinsertする
     * 
     * @param int $user_id ログインユーザーID
     * @param array $request リクエストデータ
     * @return object $result App\Models\Post
     */
    public function insertPostToRelease($user_id, $request)
    {
        $result = $this->create([
            'user_id'          => $user_id,
            'category_id'      => $request->category,
            'title'            => $request->title,
            'body'             => $request->body,
            'publish_flg'      => 1,
            'view_counter'     => 0,
            'favorite_counter' => 0,
            'delete_flg'       => 0,
        ]);
        return $result;
    }

    /**
     * 予約公開=>publish_flg=2
     * リクエストされたデータをpostsテーブルにinsertする
     * 
     * @param int $user_id ログインユーザーID
     * @param array $request リクエストデータ
     * @return object $result App\Models\Post
     */
    public function insertPostToReservationRelease($user_id, $request)
    {
        $result = $this->create([
            'user_id'          => $user_id,
            'category_id'      => $request['category'],
            'title'            => $request['title'],
            'body'             => $request['body'],
            'publish_flg'      => 2,
            'view_counter'     => 0,
            'favorite_counter' => 0,
            'delete_flg'       => 0,
        ]);
        return $result;
    }

    /**
     * 投稿IDをもとにpostsテーブルから一意の投稿データを取得
     * 
     * @param int $post_id 投稿ID
     * @return object $result App\Models\Post
     */
    public function fetchPostDataByPostId($post_id)
    {
        $result = $this->find($post_id);
        return $result;
    }

    /**
     * 記事の更新処理
     * リクエストされたデータをもとにpostデータを更新する
     *
     * @param object $request リクエストデータ
     * @param object $post 投稿データ
     * @param int $publish_status 公開ステータス (0:下書き, 1:公開, 2:予約公開)
     * @return object $result App\Models\Post
     */
    public function updatePostStatus($request, $post, $publish_status)
    {
        $result = $post->fill([
            'category_id'      => $request->category,
            'title'            => $request->title,
            'body'             => $request->body,
            'publish_flg'      => $publish_status,
        ]);

        $result->save();

        return $result;
    }

    //  /**
    //  * 記事の更新処理
    //  * 下書き保存=>publish_flg=0
    //  * リクエストされたデータをもとにpostデータを更新する
    //  *
    //  * @param array $post 投稿データ
    //  * @return object $result App\Models\Post
    //  */
    // public function updatePostToSaveDraft($request, $post)
    // {
    //     $result = $post->fill([
    //         'category_id'      => $request->category,
    //         'title'            => $request->title,
    //         'body'             => $request->body,
    //         'publish_flg'      => 0,
    //     ]);

    //     $result->save();

    //     return $result;
    // }

    // /**
    //  * 記事の更新処理
    //  * 公開=>publish_flg=1
    //  * リクエストされたデータをもとにpostデータを更新する
    //  *
    //  * @param array $post 投稿データ
    //  * @return object $result App\Models\Post
    //  */
    // public function updatePostToRelease($request, $post)
    // {
    //     $result = $post->fill([
    //         'category_id'      => $request->category,
    //         'title'            => $request->title,
    //         'body'             => $request->body,
    //         'publish_flg'      => 1,
    //     ]);

    //     $result->save();

    //     return $result;
    // }

    // /**
    //  * 記事の更新処理
    //  * 公開予約=>publish_flg=0
    //  * リクエストされたデータをもとにpostデータを更新する
    //  *
    //  * @param array $post 投稿データ
    //  * @return object $result App\Models\Post
    //  */
    // public function updatePostToReservationRelease($request, $post)
    // {
    //     $result = $post->fill([
    //         'category_id'      => $request->category,
    //         'title'            => $request->title,
    //         'body'             => $request->body,
    //         'publish_flg'      => 2,
    //     ]);

    //     $result->save();

    //     return $result;
    // }


    /**
     * 記事論理削除
     * 
     * @param int $post_id 投稿ID
     * @return object $result App\Models\Post
     */
    public function trashPost($post_id) 
    {
        $result = $this->find($post_id);
        $result->delete_flg = 1;
        $result->save();
        return $result;
    }

    /**
     * ユーザーIDに紐づいたゴミ箱の投稿データを全て取得する
     * 
     * @param int $user_id ユーザーID
     * @return Post
     */
    public function getAllTrashPostsByUserId($user_id)
    {

        $result = $this->where([
            ['user_id', $user_id],
            ['delete_flg', 1]])
            ->orderBy('updated_at', 'DESC')
            ->get();
        return $result;
    }

    /**
     * 記事の復元
     * 
     * @param int $post_id 投稿ID
     * @return object $result App\Models\Post
     */
    public function reconstructionPost($post_id)
    {
        $result = $this->find($post_id);
        $result->delete_flg = 0;
        $result->publish_flg = 0;
        $result->save();
        return $result;
    }
}
