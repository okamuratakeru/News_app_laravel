<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReservationPost;
use App\Models\Post;
use Carbon\Carbon;

class ReservationRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '予約投稿を公開する';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d H:i');
        $this->info('現在の日時: ' . $now);


        $reservations = ReservationPost::whereRaw(
            "DATE_FORMAT(CONCAT(reservation_date, ' ', reservation_time), '%Y-%m-%d %H:%i') <= ?",
            [$now]
        )->get();

        $this->info('公開する予約投稿の数: ' . $reservations->count());

        foreach ($reservations as $reservation) {
            $post = $reservation->post;

            if ($post && $post->publish_flg == 2) {
                $post->publish_flg = 1;
                $post->save();
                $reservation->delete();
            }
        }
    }
}
