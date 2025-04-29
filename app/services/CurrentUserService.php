<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
  /**
   * ログ関連サービス
   */
class CurrentUserService
{
  // ログの表示
  public function getCurrentUserId()
  {
    // ユーザーがログイン済み
    if (Auth::check()) {
      // 認証しているユーザーを取得
      $user = Auth::user();
      // 認証しているユーザーIDを取得
      $user_id = $user->id;
    } else {
        $user_id = null;
    }

    return $user_id;
  }
}