<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Jadesdev">
    <title>@yield('title', 'Dashboard') | @lang(get_setting('title', 'Jades SMM'))</title>

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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ static_asset('css/main.css') }}">
    @yield('styles')
    @stack('styles')
    @livewireStyles()
    
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen">

        @yield('content')

        {{ $slot ?? '' }}
    </div>

    <script src="{{ static_asset('js/vendors.min.js') }}"></script>
    @livewireScripts()
    {{-- <script src="{{ asset('vendor/livewire/livewire.js') }}" data-csrf="{{ csrf_token() }}"
        data-update-uri="{{ url('livewire/update') }}" data-navigate-once="true"></script> --}}
    <script src="{{ static_asset('js/main.js') }}"></script>
    @stack('scripts')
    @yield('scripts')
    @include('inc.scripts')

</body>

</html>
