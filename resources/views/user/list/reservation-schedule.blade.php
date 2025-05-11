{{-- src/resources/views/layouts/common.blade.php継承 --}}
@extends('layouts.common')

@include('user.parts.sidebar_user')
@section('content')

<div class="p-5">
    <div class="font-bold text-2xl text-center">予約公開設定</div>
    <form action="{{ route('post.reservation_store') }}" method="POST" class="pt-12 text-center">
        @csrf
        <div class="pb-5 text-2xl underline decoration-dashed decoration-amber-500">予約公開日を設定する</div>
        <label for="reservation_date">日付を選択:</label>
        <input type="date" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" required class="form-control">

        <div class="pt-12 pb-5 text-2xl underline decoration-dashed decoration-amber-500">予約公開時間を設定する</div>
        <label for="scheduled_hour">時:</label>
        <select id="scheduled_hour" name="scheduled_hour" class="form-control" required>
            @for ($i = 0; $i < 24; $i++)
                <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
            @endfor
        </select>
        <label for="scheduled_minute">分:</label>
        <select id="scheduled_minute" name="scheduled_minute" class="form-control" required>
            @for ($i = 0; $i < 60; $i += 5) <!-- 5分単位で選択 -->
                <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
            @endfor
        </select>
        <div class="pt-12">
            <button type="submit" class="inline-block px-6 py-2.5 bg-amber-500 text-white font-medium text-lg leading-tight uppercase rounded-full shadow-md hover:bg-amber-600 hover:shadow-lg focus:bg-amber-600 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-amber-700 active:shadow-lg transition duration-150 ease-in-out">上記の内容で記事を予約公開する</button>
        </div>
    </form>
</div>
@endsection