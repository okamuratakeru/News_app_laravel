<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- App Name --}}
    <title>{{ config('app.name', 'Laratto') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div>
        @yield('header')
    </div>
    <div class="flex">
        <div class="w-1/6">
            @yield('sidebar')
        </div>
        <main class="w-full">
            @yield('content')
        </main>
    </div>
    <div>
        @yield('footer')
    </div>
</body>
</html>