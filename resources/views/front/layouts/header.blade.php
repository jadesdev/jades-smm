<!-- Header -->
<header x-data="{ mobileMenuOpen: false }"
    class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center space-x-2">
                    <img src="{{ my_asset(get_setting('logo')) }}" alt="{{ get_setting('name', 'Jades SMM') }}"
                        class="w-8 h-8">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ get_setting('name', 'Jades SMM') }}
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('how-it-works') }}" wire:navigate
                    class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    How It Works
                </a>
                <a href="{{ route('services') }}" wire:navigate
                    class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    Services
                </a>
                <a href="{{ route('api-docs') }}" wire:navigate
                    class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    API
                </a>
                <a href="{{ route('terms') }}" wire:navigate
                    class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    Terms
                </a>
            </nav>

            <!-- Actions & Mobile Menu Button -->
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-2">
                    @guest
                        <a href="{{ route('login') }}" wire:navigate
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" wire:navigate
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors shadow">
                            Sign Up
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" wire:navigate
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors shadow">
                            Dashboard
                        </a>
                    @endguest
                </div>
                <button id="dark-mode-toggle"
                    class="relative p-2 rounded-lg text-gray-400 dark:text-yellow-300 hover:text-gray-500 dark:hover:text-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-500 transition-colors">
                    <i id="dark-mode-icon" class="fas fa-moon text-lg "></i>
                </button>
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        <i class="fad" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-transition
        class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
        <div class="px-4 pt-2 pb-4 space-y-2">
            <a href="{{ route('home') }}" @click="mobileMenuOpen = false" wire:navigate
                class="block py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                Home
            </a>
            <a href="{{ route('how-it-works') }}" @click="mobileMenuOpen = false" wire:navigate
                class="block py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                How It Works
            </a>
            <a href="{{ route('services') }}" @click="mobileMenuOpen = false" wire:navigate
                class="block py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                Services
            </a>
            <a href="{{ route('api-docs') }}" @click="mobileMenuOpen = false" wire:navigate
                class="block py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                API Docs
            </a>
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-3">
                @guest
                    <a href="{{ route('login') }}" wire:navigate
                        class="block w-full text-center px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" wire:navigate
                        class="block w-full text-center px-4 py-2 text-base font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors shadow">
                        Sign Up
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" wire:navigate
                        class="block w-full text-center px-4 py-2 text-base font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors shadow">
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</header>
