<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kam na dovolenku?')</title>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <script defer src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script defer src="{{ asset('assets/app.js') }}"></script>
    @stack('head')
</head>
<body>
@php($baseUrl = rtrim(config('app.url'), '/'))
<header class="topbar">
    <a class="brand" href="{{ $baseUrl }}/">
        <span class="brand-mark">✦</span>
        <span>Kam na dovolenku?</span>
    </a>
    <nav>
        <a href="{{ $baseUrl }}/">Vyhľadávanie</a>
        <a href="{{ $baseUrl }}/compare.php">Porovnanie</a>
        <a href="{{ $baseUrl }}/stats.php">Štatistiky</a>
    </nav>
</header>
<main class="shell">
    @yield('content')
</main>
</body>
</html>
