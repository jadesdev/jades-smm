<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMM Dashboard') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Compiled CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col">

        <!-- Top Bar -->
        <header
            class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-6 relative z-30">
            <!-- Left Side - Logo -->
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-gray-900">
                    <i class="fad fa-chart-line text-primary-600 mr-2"></i>
                    SMM Dashboard
                </h1>
            </div>

            <!-- Right Side - Desktop: Currency, Language, Profile | Mobile: Profile only -->
            <div class="flex items-center space-x-4">
                <!-- Currency Selector (Desktop only) -->
                <div class="hidden lg:flex items-center">
                    <select class="text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                        <option value="NGN">NGN</option>
                    </select>
                </div>

                <!-- Language Selector (Desktop only) -->
                <div class="hidden lg:flex items-center">
                    <select class="text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                        <option value="en">🇺🇸 EN</option>
                        <option value="es">🇪🇸 ES</option>
                        <option value="fr">🇫🇷 FR</option>
                        <option value="de">🇩🇪 DE</option>
                    </select>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button id="user-menu-button"
                        class="flex items-center space-x-2 p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">JD</span>
                        </div>
                        <div class="hidden lg:block text-left">
                            <p class="text-sm font-medium text-gray-900">John Doe</p>
                            <p class="text-xs text-gray-500">Admin</p>
                        </div>
                        <i class="fad fa-chevron-down text-gray-400 text-xs"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-dropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fad fa-user mr-3 text-gray-400"></i>
                            Profile
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fad fa-cog mr-3 text-gray-400"></i>
                            Settings
                        </a>
                        <div class="lg:hidden">
                            <hr class="my-1">
                            <a href="#"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fad fa-dollar-sign mr-3 text-gray-400"></i>
                                Currency
                            </a>
                            <a href="#"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fad fa-globe mr-3 text-gray-400"></i>
                                Language
                            </a>
                        </div>
                        <hr class="my-1">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fad fa-sign-out-alt mr-3 text-gray-400"></i>
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-1">
            <!-- Desktop Sidebar -->
            <aside class="hidden lg:flex lg:w-64 lg:flex-col bg-white border-r border-gray-200">
                <div class="flex-1 flex flex-col min-h-0">
                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-2">
                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 rounded-md">
                            <i class="fad fa-home w-5 mr-3"></i>
                            Dashboard
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-file-alt w-5 mr-3"></i>
                            Content
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-share-alt w-5 mr-3"></i>
                            Social Media
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-chart-bar w-5 mr-3"></i>
                            Analytics
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-calendar-alt w-5 mr-3"></i>
                            Scheduler
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-users w-5 mr-3"></i>
                            Team
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-cogs w-5 mr-3"></i>
                            Settings
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Mobile Sidebar Overlay -->
            <div id="mobile-sidebar-overlay" class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden"></div>

            <!-- Mobile Sidebar -->
            <aside id="mobile-sidebar"
                class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
                <div class="flex-1 flex flex-col min-h-0 pt-16">
                    <!-- Close Button -->
                    <div class="flex justify-end p-4">
                        <button id="mobile-sidebar-close"
                            class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fad fa-times text-lg"></i>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-2 space-y-2">
                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 rounded-md">
                            <i class="fad fa-home w-5 mr-3"></i>
                            Dashboard
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-file-alt w-5 mr-3"></i>
                            Content
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-share-alt w-5 mr-3"></i>
                            Social Media
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-chart-bar w-5 mr-3"></i>
                            Analytics
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-calendar-alt w-5 mr-3"></i>
                            Scheduler
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-users w-5 mr-3"></i>
                            Team
                        </a>

                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <i class="fad fa-cogs w-5 mr-3"></i>
                            Settings
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 lg:ml-0 flex flex-col min-h-0 pb-20 lg:pb-0">
                <div class="flex-1 p-4 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Mobile Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 lg:hidden z-20">
            <div class="flex justify-around items-center py-2">
                <a href="#" class="flex flex-col items-center py-2 px-3 text-primary-600">
                    <i class="fad fa-home text-lg"></i>
                    <span class="text-xs mt-1">Dashboard</span>
                </a>

                <a href="#" class="flex flex-col items-center py-2 px-3 text-gray-600">
                    <i class="fad fa-file-alt text-lg"></i>
                    <span class="text-xs mt-1">Content</span>
                </a>

                <a href="#" class="flex flex-col items-center py-2 px-3 text-gray-600">
                    <i class="fad fa-share-alt text-lg"></i>
                    <span class="text-xs mt-1">Social</span>
                </a>

                <a href="#" class="flex flex-col items-center py-2 px-3 text-gray-600">
                    <i class="fad fa-chart-bar text-lg"></i>
                    <span class="text-xs mt-1">Analytics</span>
                </a>

                <!-- Menu Toggle Button -->
                <button id="mobile-menu-button" class="flex flex-col items-center py-2 px-3 text-gray-600">
                    <i class="fad fa-bars text-lg"></i>
                    <span class="text-xs mt-1">Menu</span>
                </button>
            </div>
        </nav>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
            const mobileSidebarClose = document.getElementById('mobile-sidebar-close');

            function openMobileSidebar() {
                mobileSidebar.classList.remove('-translate-x-full');
                mobileSidebarOverlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeMobileSidebar() {
                mobileSidebar.classList.add('-translate-x-full');
                mobileSidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            mobileMenuButton.addEventListener('click', openMobileSidebar);
            mobileSidebarClose.addEventListener('click', closeMobileSidebar);
            mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);

            // Close sidebar when clicking on a link (mobile)
            const mobileNavLinks = mobileSidebar.querySelectorAll('nav a');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileSidebar);
            });

            // User Dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');

            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });

            // Close dropdown when clicking on a link
            const dropdownLinks = userDropdown.querySelectorAll('a');
            dropdownLinks.forEach(link => {
                link.addEventListener('click', function() {
                    userDropdown.classList.add('hidden');
                });
            });
        });
    </script>

    @stack('scripts')
    @livewireScripts()
    {{-- <script src="{{ asset('vendor/livewire/livewire.js') }}" data-csrf="{{ csrf_token() }}"
        data-update-uri="{{ url('livewire/update') }}" data-navigate-once="true"></script> --}}
</body>

</html>
