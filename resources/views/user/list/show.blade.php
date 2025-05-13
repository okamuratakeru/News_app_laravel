{{-- src/resources/views/layouts/common.blade.php継承 --}}
@extends('layouts.common')

@include('user.parts.sidebar_user')
@section('content')
<div>
    @if($post->publish_flg === 2)
        <p>予約公開日時: {{ $post->reservation_posts->reservation_date }} {{ $post->reservation_posts->reservation_time }}</p>
    @endif
</div>
<div class="px-8 py-8 mx-auto bg-white">
    <div class="flex items-center justify-between">
        <span class="text-sm font-light text-gray-600">{{ $post->updated_at->format('Y/m/d') }}</span>
    </div>

    <div class="mt-2">
        <p class="text-2xl font-bold text-gray-800">{{ $post->title }}</p>
        <p class="mt-8 text-gray-600">{{ $post->body }}</p>
    </div>
</div>
@endsection 