<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'category_name',
        'created_at',
        'updated_at'
    ];

    /**
     * Postモデルとリレーション
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * カテゴリーを全て取得
     * 
     * @return App\Model\Category $result カテゴリーモデル
     */
    public function getAllCategories()
    {
        $result = $this->get();
        return $result;
    }
}
