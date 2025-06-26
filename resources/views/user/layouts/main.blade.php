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
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="{{ static_asset('css/vendors.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
    @stack('styles')
    @livewireStyles()
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('user.layouts.sidebar')

        <!-- Mobile sidebar overlay -->
        <div id="mobile-sidebar-overlay" class="fixed inset-0 z-40 hidden md:hidden"
            style="background-color: rgba(243, 244, 246, 0.35);"></div>

        <!-- Mobile sidebar -->
        @include('user.layouts.sidebar-mobile')

        <!-- Mobile bottom navigation -->
        @include('user.layouts.footer')

        <!-- Main content area -->
        <div class="flex-1 overflow-auto pb-16 md:pb-0">
            <!-- Top header -->
            @include('user.layouts.header')
            <!-- Main content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="{{ static_asset('js/vendors.js') }}"></script>
    <script src="{{ static_asset('js/main.js') }}"></script>
    @livewireScripts()
    {{-- <script src="{{ asset('vendor/livewire/livewire.js') }}" data-csrf="{{ csrf_token() }}"
        data-update-uri="{{ url('livewire/update') }}" data-navigate-once="true"></script> --}}
    @stack('scripts')
    @yield('scripts')
    @include('inc.scripts')
</body>

</html>
