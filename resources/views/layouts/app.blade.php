<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JobTrack')</title>
    <link rel="stylesheet" href="{{ asset('css/jobtrack.css') }}">
</head>
<body>
    <div class="app-shell">
        <header class="site-header">
            <div class="site-header__inner">
                <a class="brand" href="{{ route('applications.index') }}" aria-label="JobTrack home">
                    <span class="brand__mark" aria-hidden="true">JT</span>
                    <span class="brand__text">
                        <span class="brand__name">JobTrack</span>
                        <span class="brand__caption">APPLICATION TRACKER</span>
                    </span>
                </a>
                <nav class="primary-nav" aria-label="Main navigation">
                    <a href="{{ route('applications.index') }}" @if (request()->routeIs('applications.index')) aria-current="page" @endif>Applications</a>
                    <a href="{{ route('applications.create') }}" @if (request()->routeIs('applications.create')) aria-current="page" @endif>Add application</a>
                </nav>
            </div>
        </header>

        <main class="page-container page-enter">
            @yield('content')
        </main>
    </div>
</body>
</html>
