<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Jadesdev">
    <title>@yield('title', 'Dashboard') | @lang(get_setting('title', 'Social Rocket'))</title>
    <link rel="icon shortcut" href="{{my_asset(get_setting('favicon'))}}">

    @yield('meta')
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    <script>
        (function() {
            const theme = localStorage.getItem('color-theme') ||
                (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="{{ static_asset('css/vendors.min.css') }}">
    @if (!config('livewire.server'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ static_asset('css/styles.css') }}">
        <script src="{{ static_asset('js/app.js') }}" defer></script>
    @endif
    <link rel="stylesheet" href="{{ static_asset('css/main.css') }}">
    @yield('styles')
    @stack('styles')
    @livewireStyles()

</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
    @include('front.layouts.header')

    <main class="flex-grow">
        @yield('content')

        {{ $slot ?? '' }}
    </main>
    @include('front.layouts.footer')

    <script src="{{ static_asset('js/vendors.min.js') }}"></script>
    @if (!config('livewire.server'))
        @livewireScripts()
    @else
        <script src="{{ asset('public/vendor/livewire/livewire.js') }}" data-csrf="{{ csrf_token() }}"
            data-update-uri="{{ url('livewire/update') }}" data-navigate-once="true"></script>
    @endif
    <script src="{{ static_asset('js/main.js') }}"></script>
    @stack('scripts')
    @yield('scripts')
    @include('inc.scripts')

</body>

</html>
