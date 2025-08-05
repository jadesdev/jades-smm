<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error') - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css'])
    <link rel="icon shortcut" href="{{ my_asset(get_setting('favicon', 'favicon.png')) }}">
    <link rel="stylesheet" href="{{ static_asset('css/styles.css') }}">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-lg w-full">
        <div class="text-center">
            <div class="mb-8">
                @yield('illustration')
            </div>

            <!-- Error Content -->
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10">
                <div class="mb-6">
                    <h1 class="text-6xl md:text-7xl font-bold text-gray-200 mb-2">
                        @yield('code')
                    </h1>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                        @yield('message')
                    </h2>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        @yield('description')
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    @yield('actions')

                    <!-- Default Back Button -->
                    @if (!View::hasSection('actions'))
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ url()->previous() }}"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gray-800 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Go Back
                            </a>
                            <a href="{{ route('home') ?? '/' }}"
                                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Home
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-gray-500 text-sm">
                    Need help? <a href="mailto:support@example.com"
                        class="text-blue-600 hover:text-blue-800 transition-colors">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
