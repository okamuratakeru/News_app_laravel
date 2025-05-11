<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\ReservationPost;
use App\Services\CurrentUserService;

class ReservationPostController extends Controller
{
    private $post;
    private $category;
    private $reservationPost;
    private $currentUserService;
    public function __construct()
    {
        $this->post = new Post();
        $this->category = new Category();
        $this->reservationPost = new ReservationPost();
        $this->currentUserService = new CurrentUserService();
    }

    /**
     * 予約画面
     * 
     * @return Response src/resources/views/user/list/reservation-schedule.blade.phpを表示
     */
    public function reservation(Request $request) {
        $input = $request->all();
        //laravelでのsessionの使い方を調べる
        $request->session()->put("reservation_input", $input);
        return view('user.list.reservation-schedule');
    }

    /**
     * 予約公開
     * 
     * @return Response src/resources/views/user/list/reservation-schedule.blade.phpを表示
     */
    public function reservation_store(Request $request) {
        $user_id = $this->currentUserService->getCurrentUserId();
        $input = $request->session()->get("reservation_input");
        $post = $this->post->insertPostToReservationRelease($user_id, $input);

        $date = $request->scheduled_date;
        $time = sprintf('%02d:%02d', $request->scheduled_hour, $request->scheduled_minute);
        
        ReservationPost::create([
            'user_id' => $post->user_id,
            'post_id' => $post->id,
            'reservation_date' => $date,
            'reservation_time' => $time,
        ]);

        return redirect()->route('user.index', ['id' => $user_id]);
    }
}
