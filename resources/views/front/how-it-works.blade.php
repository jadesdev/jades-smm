@extends('front.layouts.main')

@section('title', 'How It Works')

@section('content')
    <div class="bg-white dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">

            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Start Growing in <span class="text-primary-600 dark:text-primary-400">3 Simple Steps</span>
                </h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-300">
                    Our platform is designed to be intuitive and powerful. Follow these simple steps to begin boosting your
                    social media presence instantly.
                </p>
            </div>

            <div class="relative max-w-4xl mx-auto">
                <div class="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-gray-200 dark:bg-gray-700 -z-1"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center relative z-10">

                    <div class="flex flex-col items-center">
                        <div
                            class="w-24 h-24 bg-primary-100 dark:bg-primary-900/50 rounded-full flex items-center justify-center mb-6 border-4 border-white dark:border-gray-800 shadow-lg">
                            <i class="fad fa-user-plus text-4xl text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            <span class="text-primary-500 dark:text-primary-400 font-semibold text-sm block mb-1">Step
                                1</span>
                            Create Your Account
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Sign up for a free account in under a minute. No complicated forms, just the essentials to get
                            you started on your growth journey.
                        </p>
                    </div>

                    <div class="flex flex-col items-center">
                        <div
                            class="w-24 h-24 bg-primary-100 dark:bg-primary-900/50 rounded-full flex items-center justify-center mb-6 border-4 border-white dark:border-gray-800 shadow-lg">
                            <i class="fad fa-wallet text-4xl text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            <span class="text-primary-500 dark:text-primary-400 font-semibold text-sm block mb-1">Step
                                2</span>
                            Add Funds
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Securely deposit funds into your wallet using our various payment methods, including credit
                            cards and cryptocurrencies.
                        </p>
                    </div>

                    <div class="flex flex-col items-center">
                        <div
                            class="w-24 h-24 bg-primary-100 dark:bg-primary-900/50 rounded-full flex items-center justify-center mb-6 border-4 border-white dark:border-gray-800 shadow-lg">
                            <i class="fad fa-shopping-cart text-4xl text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            <span class="text-primary-500 dark:text-primary-400 font-semibold text-sm block mb-1">Step
                                3</span>
                            Place Your Order
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Choose from hundreds of high-quality services, enter your link, and watch as we deliver your
                            order instantly.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="bg-gray-100 dark:bg-gray-700 py-16 lg:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Image Column -->
                <div>
                    <img src="{{ static_asset('images/dashboard.png') }}"
                        alt="SMM Panel Dashboard" class="rounded-xl shadow-2xl">
                </div>

                <!-- Content Column -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Unlock Your Full Potential</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Our simple process is backed by powerful features designed for reliability, speed, and scale. Here’s
                        what makes our service the best choice for your growth.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fad fa-check-circle text-primary-500 mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-semibold text-gray-900 dark:text-white">Instant Order Start</h4>
                                <p class="text-gray-600 dark:text-gray-400">No waiting around. Our automated system
                                    processes your orders the moment you place them.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fad fa-check-circle text-primary-500 mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-semibold text-gray-900 dark:text-white">Real-time Tracking</h4>
                                <p class="text-gray-600 dark:text-gray-400">Monitor the progress of your orders directly
                                    from your dashboard with live start counts and remaining quantities.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fad fa-check-circle text-primary-500 mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-semibold text-gray-900 dark:text-white">24/7 Customer Support</h4>
                                <p class="text-gray-600 dark:text-gray-400">Our dedicated support team is available around
                                    the clock to assist you with any questions or issues.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fad fa-check-circle text-primary-500 mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-semibold text-gray-900 dark:text-white">Powerful API for Resellers</h4>
                                <p class="text-gray-600 dark:text-gray-400">Integrate our services seamlessly into your own
                                    platform with our robust and easy-to-use API.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Ready to See the Results?</h2>
            <p class="mt-4 max-w-2xl mx-auto text-gray-600 dark:text-gray-300">Join our community of marketers and
                influencers who are already scaling their social media presence. Your journey starts now.</p>
            <div class="mt-8">
                <a href="{{ route('register') }}"
                    class="inline-block px-10 py-4 text-lg font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg shadow-lg transform hover:scale-105 transition-all">
                    Create Your Free Account
                </a>
            </div>
        </div>
    </section>
@endsection
