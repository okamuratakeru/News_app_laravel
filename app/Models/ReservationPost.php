<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Post;

class ReservationPost extends Model
{
    use HasFactory;

    protected $table = 'reservation_posts';

    protected $fillable = [
        'user_id',
        'post_id',
        'reservation_date',
        'reservation_time',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
