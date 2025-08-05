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
    @if (!config('livewire.server'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ static_asset('css/styles.css') }}">
        <script src="{{ static_asset('js/app.js') }}" defer></script>
    @endif
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/toolbar/prism-toolbar.min.css"
        rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ static_asset('css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    @yield('styles')
    @stack('styles')
    @livewireStyles()
</head>

<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <div class="flex h-screen overflow-hidden ">
        @include('admin.layouts.sidebar')

        <!-- Mobile sidebar overlay -->
        <div id="mobile-sidebar-overlay" class="fixed inset-0 z-40 hidden lg:hidden"
            style="background-color: rgba(243, 244, 246, 0.35);"></div>

        <!-- Mobile sidebar -->
        @include('admin.layouts.sidebar-mobile')

        <!-- Main content area -->
        <div class="flex-1 overflow-auto pb-16 md:pb-0">
            <!-- Top header -->
            @include('admin.layouts.header')
            <!-- Main content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                        @yield('content')

                        {{ $slot ?? '' }}
                    </div>
                </div>
            </main>
        </div>
    </div>

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
    <script defer src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/toolbar/prism-toolbar.min.js"
        crossorigin="anonymous"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

</body>

</html>
