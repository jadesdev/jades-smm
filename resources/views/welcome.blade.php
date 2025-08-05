<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang(get_setting('title', 'Jades SMM')) - The #1 SMM Panel</title>

    <!-- Meta Tags for SEO -->
    <meta name="description" content="{{ get_setting('meta_description', 'The ultimate SMM panel for resellers and agencies. Get instant likes, followers, views, and more at unbeatable prices.') }}">
    <meta name="keywords" content="{{ get_setting('meta_keywords', 'smm panel, social media marketing, instagram followers, youtube views, tiktok likes, best smm panel') }}">
    <meta name="author" content="Jadesdev">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Dark Mode Initializer -->
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

    <!-- Assets -->
    <link rel="stylesheet" href="{{ static_asset('css/vendors.min.css') }}">
    @if (!config('livewire.server'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ static_asset('build/app-Ckbkdahw.css') }}">
        <script src="{{ static_asset('build/app-l0sNRNKZ.js') }}" defer></script>
    @endif
    <link rel="stylesheet" href="{{ static_asset('css/main.css') }}">
    @livewireStyles
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 font-sans antialiased">

    <!-- Header -->
    <header x-data="{ mobileMenuOpen: false }" class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <i class="fad fa-rocket text-2xl text-primary-600 dark:text-primary-400"></i>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">{{ get_setting('name', 'SMM Panel') }}</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Features</a>
                    <a href="#services" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Services</a>
                    <a href="#how-it-works" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">How It Works</a>
                </nav>

                <!-- Actions & Mobile Menu Button -->
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-2">
                         <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors shadow">
                            Sign Up
                        </a>
                    </div>
                     <button id="dark-mode-toggle" class="relative p-2 rounded-lg text-gray-400 dark:text-yellow-300 hover:text-gray-500 dark:hover:text-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-500 transition-colors">
                        <i id="dark-mode-icon" class="fas fa-moon text-lg "></i>
                    </button>
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <i class="fad" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="#features" @click="mobileMenuOpen = false" class="block py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Features</a>
                <a href="#services" @click="mobileMenuOpen = false" class="block py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Services</a>
                <a href="#how-it-works" @click="mobileMenuOpen = false" class="block py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">How It Works</a>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-3">
                     <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md transition-colors">Login</a>
                     <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 text-base font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors shadow">Sign Up</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="relative pt-20 pb-12 lg:pt-32 lg:pb-20 bg-gray-50 dark:bg-gray-900 overflow-hidden">
             <div class="absolute inset-0 bg-gradient-to-br from-primary-50/50 to-purple-50/50 dark:from-primary-900/20 dark:to-purple-900/20"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
                    Elevate Your Social <span class="text-primary-600 dark:text-primary-400">Media Presence</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-300">
                    The ultimate SMM panel for resellers and agencies. Get instant likes, followers, views, and more at unbeatable prices, backed by our 24/7 support.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-block px-8 py-3 text-lg font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg shadow-lg transform hover:scale-105 transition-all">
                        Get Started Now
                    </a>
                     <a href="#services" class="w-full sm:w-auto inline-block px-8 py-3 text-lg font-semibold text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg shadow-lg transform hover:scale-105 transition-all">
                        View Services
                    </a>
                </div>
            </div>
        </section>

        <!-- Live Statistics Bar -->
        <div class="bg-white dark:bg-gray-800 py-6 border-b border-t border-gray-200 dark:border-gray-700">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    {{-- TODO: These stats should be dynamic from your backend --}}
                    <div>
                        <div class="text-3xl font-bold text-primary-600 dark:text-primary-400">12,500+</div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Orders</p>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-primary-600 dark:text-primary-400">1,200+</div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Happy Clients</p>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-primary-600 dark:text-primary-400">500+</div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Services Available</p>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-primary-600 dark:text-primary-400">24/7</div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Support</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <section id="features" class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Why Choose Us?</h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">The best features to power your social media growth.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature Card 1 -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center mb-4">
                            <i class="fad fa-bolt text-2xl text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Instant Delivery</h3>
                        <p class="text-gray-600 dark:text-gray-400">Our services start delivering within seconds of placing an order, ensuring rapid results for your campaigns.</p>
                    </div>
                    <!-- Feature Card 2 -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center mb-4">
                            <i class="fad fa-shield-check text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">High-Quality Services</h3>
                        <p class="text-gray-600 dark:text-gray-400">We provide top-tier, stable services to ensure your social media accounts grow safely and effectively.</p>
                    </div>
                    <!-- Feature Card 3 -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center mb-4">
                            <i class="fad fa-code text-2xl text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">API for Developers</h3>
                        <p class="text-gray-600 dark:text-gray-400">Easily integrate our services into your own applications with our powerful and well-documented API.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-16 lg:py-24 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Our Most Popular Services</h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">A glimpse of our wide range of social media solutions.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Service Item -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 text-center border border-gray-200 dark:border-gray-700 shadow-sm transition-all hover:scale-105">
                        <i class="fab fa-instagram text-4xl text-pink-500 mb-4"></i>
                        <h4 class="font-semibold text-lg dark:text-white">Instagram Followers</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">from <span class="font-bold text-primary-600 dark:text-primary-400">$0.50</span> / 1k</p>
                    </div>
                     <!-- Service Item -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 text-center border border-gray-200 dark:border-gray-700 shadow-sm transition-all hover:scale-105">
                        <i class="fab fa-youtube text-4xl text-red-500 mb-4"></i>
                        <h4 class="font-semibold text-lg dark:text-white">YouTube Views</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">from <span class="font-bold text-primary-600 dark:text-primary-400">$1.20</span> / 1k</p>
                    </div>
                     <!-- Service Item -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 text-center border border-gray-200 dark:border-gray-700 shadow-sm transition-all hover:scale-105">
                        <i class="fab fa-tiktok text-4xl text-black dark:text-white mb-4"></i>
                        <h4 class="font-semibold text-lg dark:text-white">TikTok Likes</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">from <span class="font-bold text-primary-600 dark:text-primary-400">$0.80</span> / 1k</p>
                    </div>
                     <!-- Service Item -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 text-center border border-gray-200 dark:border-gray-700 shadow-sm transition-all hover:scale-105">
                        <i class="fab fa-facebook text-4xl text-blue-600 mb-4"></i>
                        <h4 class="font-semibold text-lg dark:text-white">Facebook Page Likes</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">from <span class="font-bold text-primary-600 dark:text-primary-400">$1.50</span> / 1k</p>
                    </div>
                </div>
                 <div class="text-center mt-10">
                     <a href="{{ route('register') }}" class="px-6 py-3 text-base font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors shadow">
                        View All Services
                    </a>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
             <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Get Started in 3 Easy Steps</h2>
                </div>
                <div class="relative">
                    <!-- Connecting Line -->
                    <div class="hidden md:block absolute top-8 left-0 w-full h-0.5 bg-gray-200 dark:bg-gray-700"></div>
                    
                    <div class="relative grid grid-cols-1 md:grid-cols-3 gap-12">
                         <!-- Step 1 -->
                        <div class="text-center">
                            <div class="relative w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-primary-500 dark:border-primary-400 shadow-lg">
                                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">1</span>
                            </div>
                            <h3 class="text-xl font-semibold dark:text-white mb-2">Register Account</h3>
                            <p class="text-gray-600 dark:text-gray-400">Create your free account in just a few clicks to get access to our panel.</p>
                        </div>
                         <!-- Step 2 -->
                        <div class="text-center">
                            <div class="relative w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-primary-500 dark:border-primary-400 shadow-lg">
                                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">2</span>
                            </div>
                            <h3 class="text-xl font-semibold dark:text-white mb-2">Add Funds</h3>
                            <p class="text-gray-600 dark:text-gray-400">Deposit funds into your account using one of our secure payment methods.</p>
                        </div>
                         <!-- Step 3 -->
                        <div class="text-center">
                            <div class="relative w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-primary-500 dark:border-primary-400 shadow-lg">
                                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">3</span>
                            </div>
                            <h3 class="text-xl font-semibold dark:text-white mb-2">Place Order</h3>
                            <p class="text-gray-600 dark:text-gray-400">Select your desired service, enter the link, and watch your social media grow.</p>
                        </div>
                    </div>
                </div>
             </div>
        </section>
        <section id="faq" class="py-16 lg:py-24 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Frequently Asked Questions</h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Have questions? We have answers.</p>
                </div>
                <div x-data="{ open: 1 }" class="max-w-3xl mx-auto space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <button @click="open = open === 1 ? null : 1" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="text-lg font-medium text-gray-900 dark:text-white">What is an SMM Panel?</span>
                            <i class="fad fa-chevron-down transition-transform" :class="{ 'rotate-180': open === 1 }"></i>
                        </button>
                        <div x-show="open === 1" x-collapse class="px-6 pb-6 text-gray-600 dark:text-gray-300">
                            <p>An SMM (Social Media Marketing) Panel is an online shop where you can buy social media services like followers, likes, views, etc. It's a one-stop-shop for enhancing your social media presence quickly and affordably.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 2 -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <button @click="open = open === 2 ? null : 2" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="text-lg font-medium text-gray-900 dark:text-white">Are the services safe to use?</span>
                            <i class="fad fa-chevron-down transition-transform" :class="{ 'rotate-180': open === 2 }"></i>
                        </button>
                        <div x-show="open === 2" x-collapse class="px-6 pb-6 text-gray-600 dark:text-gray-300">
                            <p>Absolutely. We provide high-quality, stable services that are safe for your accounts. We never ask for your password, and our methods comply with the terms of service of all major social media platforms.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 3 -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <button @click="open = open === 3 ? null : 3" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="text-lg font-medium text-gray-900 dark:text-white">How long does it take for my order to be delivered?</span>
                            <i class="fad fa-chevron-down transition-transform" :class="{ 'rotate-180': open === 3 }"></i>
                        </button>
                        <div x-show="open === 3" x-collapse class="px-6 pb-6 text-gray-600 dark:text-gray-300">
                            <p>Most of our services start delivering instantly, within seconds or minutes of placing an order. Each service has an estimated start time mentioned in its description.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 4 -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <button @click="open = open === 4 ? null : 4" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="text-lg font-medium text-gray-900 dark:text-white">What is Drip-feed?</span>
                            <i class="fad fa-chevron-down transition-transform" :class="{ 'rotate-180': open === 4 }"></i>
                        </button>
                        <div x-show="open === 4" x-collapse class="px-6 pb-6 text-gray-600 dark:text-gray-300">
                            <p>Drip-feed is a feature that allows you to get your order delivered gradually over a period of time instead of all at once. For example, you can get 100 likes per day for 10 days, making the growth look more natural.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Testimonials -->
        <section class="py-16 lg:py-24 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">What Our Clients Say</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-gray-50 dark:bg-gray-900 p-8 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex text-yellow-400 mb-4">★★★★★</div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">"The best SMM panel I've ever used. The delivery is incredibly fast and the support team is always helpful. Highly recommended!"</p>
                        <div class="flex items-center">
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900 dark:text-white">Jane Doe</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Digital Marketer</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-8 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex text-yellow-400 mb-4">★★★★★</div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">"I run a small agency and this panel is a lifesaver. The API is robust and the prices are unbeatable for resellers."</p>
                        <div class="flex items-center">
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900 dark:text-white">John Smith</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Agency Owner</p>
                            </div>
                        </div>
                    </div>
                     <div class="bg-gray-50 dark:bg-gray-900 p-8 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex text-yellow-400 mb-4">★★★★★</div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">"Affordable, reliable, and super easy to use. I was able to grow my personal brand's engagement significantly in just a few weeks."</p>
                        <div class="flex items-center">
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900 dark:text-white">Emily White</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Content Creator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-gray-100 dark:bg-gray-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
                 <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Ready to Boost Your Social Media?</h2>
                 <p class="mt-4 max-w-2xl mx-auto text-gray-600 dark:text-gray-300">Join thousands of satisfied clients who trust us for their social media marketing needs. Sign up today and get started!</p>
                 <div class="mt-8">
                     <a href="{{ route('register') }}" class="inline-block px-10 py-4 text-lg font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg shadow-lg transform hover:scale-105 transition-all">
                        Create Your Account
                    </a>
                 </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">We accept the following payment methods:</p>
                <div class="flex justify-center items-center space-x-4 mt-4 text-3xl text-gray-500 dark:text-gray-400">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-paypal"></i>
                    <i class="fab fa-bitcoin"></i>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 border-t border-gray-200 dark:border-gray-700 pt-6">
                 <p class="text-sm text-gray-600 dark:text-gray-400">&copy; {{ date('Y') }} {{ get_setting('name', 'SMM Panel') }}. All rights reserved.</p>
                 <div class="flex items-center space-x-4">
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><i class="fab fa-instagram"></i></a>
                 </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ static_asset('js/vendors.min.js') }}"></script>
    <script src="{{ static_asset('js/main.js') }}"></script>
    @include('inc.scripts')
    @livewireScripts
</body>
</html>