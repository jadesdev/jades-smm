@extends('front.layouts.main')
@section('title', 'Home')

@section('content')

    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0">
            <div
                class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-purple-600/20 to-pink-600/20 dark:from-blue-900/30 dark:via-purple-900/30 dark:to-pink-900/30">
            </div>
            <div class="absolute top-20 left-20 w-32 h-32 bg-blue-500/20 rounded-full blur-xl float-animation"></div>
            <div class="absolute bottom-20 right-20 w-40 h-40 bg-purple-500/20 rounded-full blur-xl float-animation"
                style="animation-delay: -2s;">
            </div>
            <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-pink-500/20 rounded-full blur-xl float-animation"
                style="animation-delay: -4s;">
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Live Activity Ticker -->
            <div class="mb-8 inline-flex items-center px-4 py-2 glass-effect rounded-full text-sm">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                <span class="text-gray-700 dark:text-gray-300">🔥
                    <span id="liveOrders">1,247</span> orders completed today
                </span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black mb-6 fade-in-up">
                Supercharge Your
                <span class="gradient-text block">Social Growth</span>
            </h1>

            <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-600 dark:text-gray-300 fade-in-up"
                style="animation-delay: 0.2s;">
                The #1 SMM panel trusted by <strong>50,000+ marketers</strong> worldwide.
                Get instant likes, followers, and views with our premium services.
            </p>

            <!-- Enhanced CTA Section -->
            <div class="mt-12 fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-6">
                    <a href="{{ route('register') }}" wire:navigate
                        class="group relative w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-purple-500 rounded-xl shadow-2xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-rocket mr-2 group-hover:animate-pulse"></i>
                        Start Free
                        <div
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
                            {{ format_price(500) }} Bonus
                        </div>
                    </a>
                    <a href="{{ route('how-it-works') }}" wire:navigate
                        class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <i class="fas fa-play mr-2"></i>
                        How It Works
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="flex items-center justify-center gap-6 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center">
                        <i class="fas fa-shield-check text-green-500 mr-1"></i>
                        SSL Secured
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock text-blue-500 mr-1"></i>
                        Instant Delivery
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-headset text-purple-500 mr-1"></i>
                        24/7 Support
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="bg-white dark:bg-gray-800 py-8 border-t border-b border-gray-200 dark:border-gray-700 shadow-inner">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="count-up" style="animation-delay: 0.1s;">
                    <div class="text-4xl font-black gradient-text" x-data="{ count: 0 }" x-init="setTimeout(() => {
                        let target = 127539;
                        let increment = target / 100;
                        let timer = setInterval(() => {
                            count += increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                        }, 20);
                    }, 500)">
                        <span x-text="Math.floor(count).toLocaleString()">0</span>+
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">Orders Delivered</p>
                </div>
                <div class="count-up" style="animation-delay: 0.2s;">
                    <div class="text-4xl font-black gradient-text" x-data="{ count: 0 }" x-init="setTimeout(() => {
                        let target = 8742;
                        let increment = target / 100;
                        let timer = setInterval(() => {
                            count += increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                        }, 20);
                    }, 700)">
                        <span x-text="Math.floor(count).toLocaleString()">0</span>+
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">Happy Customers</p>
                </div>
                <div class="count-up" style="animation-delay: 0.3s;">
                    <div class="text-4xl font-black gradient-text" x-data="{ count: 0 }" x-init="setTimeout(() => {
                        let target = 847;
                        let increment = target / 100;
                        let timer = setInterval(() => {
                            count += increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                        }, 20);
                    }, 900)">
                        <span x-text="Math.floor(count)">0</span>+
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">Premium Services</p>
                </div>
                <div class="count-up" style="animation-delay: 0.4s;">
                    <div class="text-4xl font-black text-green-500">99.9%</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">Uptime Guarantee</p>
                </div>
            </div>
        </div>
    </div>

    <section class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    Why Choose <span class="gradient-text">Our Platform</span>?
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Advanced features that set us apart from the competition
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    class="group bg-white dark:bg-gray-800 p-8 rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/10 dark:to-purple-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-bolt text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Lightning Fast</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Our automated system starts delivering within <strong>30 seconds</strong> of order placement. No
                            more waiting around.
                        </p>
                        <div class="mt-4 text-sm text-blue-600 dark:text-blue-400 font-semibold">
                            ⚡ Average start time: 15 seconds
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-white dark:bg-gray-800 p-8 rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shield-check text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Premium Quality</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            High-retention services from real, active accounts. Your growth looks natural and stays
                            permanent.
                        </p>
                        <div class="mt-4 text-sm text-green-600 dark:text-green-400 font-semibold">
                            🛡️ 90-day retention guarantee
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-white dark:bg-gray-800 p-8 rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/10 dark:to-pink-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-500 to-primary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-code text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Developer API</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            RESTful API with comprehensive documentation. Build your own panel or integrate seamlessly.
                        </p>
                        <div class="mt-4 text-sm text-blue-600 dark:text-blue-400 font-semibold">
                            🚀 99.9% API uptime
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-16 lg:py-24 bg-gray-100 dark:bg-gray-700">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Get Started in 3 Easy Steps</h2>
            </div>
            <div class="relative">
                <div class="hidden md:block absolute top-8 left-0 w-full h-0.5 bg-gray-200 dark:bg-gray-700"></div>

                <div class="relative grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div
                            class="relative w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-primary-500 dark:border-primary-400 shadow-lg">
                            <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">1</span>
                        </div>
                        <h3 class="text-xl font-semibold dark:text-white mb-2">Register Account</h3>
                        <p class="text-gray-600 dark:text-gray-400">Create your free account in just a few clicks to get
                            access to our panel.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="text-center">
                        <div
                            class="relative w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-primary-500 dark:border-primary-400 shadow-lg">
                            <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">2</span>
                        </div>
                        <h3 class="text-xl font-semibold dark:text-white mb-2">Add Funds</h3>
                        <p class="text-gray-600 dark:text-gray-400">Deposit funds into your account using one of our secure
                            payment methods.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="text-center">
                        <div
                            class="relative w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-primary-500 dark:border-primary-400 shadow-lg">
                            <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">3</span>
                        </div>
                        <h3 class="text-xl font-semibold dark:text-white mb-2">Place Order</h3>
                        <p class="text-gray-600 dark:text-gray-400">Select your desired service, enter the link, and watch
                            your social media grow.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- services --}}
    <section class="py-20 bg-white dark:bg-gray-800" x-data="{ activeTab: 'instagram' }">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    <span class="gradient-text">Premium Services</span> for Every Platform
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">Choose your platform and see our competitive pricing
                </p>
            </div>

            <!-- Platform Tabs -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button @click="activeTab = 'instagram'"
                    :class="activeTab === 'instagram' ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white' :
                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                    <i class="fab fa-instagram mr-2"></i>Instagram
                </button>
                <button @click="activeTab = 'facebook'"
                    :class="activeTab === 'facebook' ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white' :
                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                    <i class="fab fa-facebook mr-2"></i>Facebook
                </button>
                <button @click="activeTab = 'youtube'"
                    :class="activeTab === 'youtube' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white' :
                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                    <i class="fab fa-youtube mr-2"></i>YouTube
                </button>
                <button @click="activeTab = 'tiktok'"
                    :class="activeTab === 'tiktok' ? 'bg-gradient-to-r from-gray-800 to-gray-900 text-white' :
                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                    <i class="fab fa-tiktok mr-2"></i>TikTok
                </button>
                <button @click="activeTab = 'telegram'"
                    :class="activeTab === 'telegram' ? 'bg-gradient-to-r from-blue-400 to-blue-600 text-white' :
                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                    <i class="fab fa-telegram mr-2"></i>Telegram
                </button>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                @foreach (['instagram', 'facebook', 'youtube', 'tiktok', 'telegram'] as $platform)
                    <template x-if="activeTab === '{{ $platform }}'">
                        <div class="contents">
                            @if (isset($services[$platform]) && count($services[$platform]) > 0)
                                @foreach ($services[$platform] as $service)
                                    <div x-transition
                                        class="bg-gradient-to-br {{ $service['bg'] }} {{ $service['darkBg'] }} rounded-2xl p-6 text-center border-2 {{ $service['border'] }} {{ $service['darkBorder'] }} hover:scale-105 transition-transform duration-300 cursor-pointer"
                                        onclick="window.location.href='{{ route('user.orders.create') }}?service_id={{ $service['id'] }}'">
                                        <div
                                            class="w-16 h-16 bg-gradient-to-br {{ $service['gradient'] }} rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="{{ $service['icon'] }} text-white text-xl"></i>
                                        </div>
                                        <h4 class="font-bold text-lg dark:text-white mb-2">{{ $service['title'] }}</h4>
                                        <div class="text-3xl font-black gradient-text mb-2">
                                            {{ format_price($service['price'], 2) }}
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ $service['unit'] }}
                                        </p>
                                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                            @foreach ($service['features'] as $feature)
                                                <div>✓ {{ $feature }}</div>
                                            @endforeach
                                        </div>
                                        <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                                            Min: {{ number_format($service['min']) }} • Max:
                                            {{ number_format($service['max']) }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-span-4 text-center py-12">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-exclamation-circle text-4xl mb-4"></i>
                                        <p class="text-lg">No services available for {{ ucfirst($platform) }} yet.</p>
                                        <p class="text-sm">Check back soon for updates!</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </template>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a class="px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
                    href="{{ route('services') }}" wire:navigate>
                    View All Services
                </a>
            </div>
        </div>
    </section>

    <section id="faq" class="py-12 lg:py-18 bg-gray-200 dark:bg-gray-700">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Frequently Asked Questions</h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Have questions? We have answers.</p>
            </div>
            <div x-data="{ open: 1 }" class="max-w-3xl mx-auto space-y-4">
                @foreach ($faqs as $index => $faq)
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <button @click="open = open === {{ $index + 1 }} ? null : {{ $index + 1 }}"
                            class="w-full flex items-center justify-between p-6 text-left">
                            <span class="text-lg font-medium text-gray-900 dark:text-white">{{ $faq['question'] }}</span>
                            <i class="fad fa-chevron-down transition-transform"
                                :class="{ 'rotate-180': open === {{ $index + 1 }} }"></i>
                        </button>
                        <div x-show="open === {{ $index + 1 }}" x-collapse
                            class="px-6 pb-6 text-gray-600 dark:text-gray-300">
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">What Our Clients Say</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($testimonials as $testimonial)
                    <div class="bg-gray-50 dark:bg-gray-900 p-8 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex text-yellow-400 mb-4">★★★★★</div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">"{{ $testimonial['quote'] }}"</p>
                        <div class="flex items-center">
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $testimonial['name'] }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $testimonial['title'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                Ready to <span class="text-yellow-300">Boost Your </span> Social Media?
            </h2>
            <p class="text-xl text-white/90 max-w-2xl mx-auto mb-8">
                Join thousands of satisfied clients who
                trust us for their social media marketing needs. Sign up today and get started!
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                <a href="{{ route('register') }}" wire:navigate
                    class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-4 text-xl font-bold text-gray-900 bg-white rounded-xl shadow-2xl hover:bg-gray-100 transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-user-plus mr-2 text-yellow-500"></i>
                    Claim $5 Free Credit
                </a>
                <a href="{{ route('user.support.create') }}" wire:navigate
                    class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-4 text-xl font-bold text-white border-2 border-white rounded-xl hover:bg-white hover:text-gray-900 transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-headset mr-2"></i>
                    Talk to Expert
                </a>
            </div>

            <div class="flex items-center justify-center gap-8 text-white/80 text-sm">
                <div>🛡️ Premium Quality</div>
                <div>⚡ Instant activation</div>
                <div>🔒 100% secure</div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes countUp {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .count-up {
            animation: countUp 0.6s ease-out forwards;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #2f21ca 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('livewire:navigated', function() {
            initIndexPageScripts();
        });

        document.addEventListener('livewire:navigating', function() {
            cleanupIndexPageScripts();
        });

        document.addEventListener('DOMContentLoaded', function() {
            initIndexPageScripts();
        });

        function initIndexPageScripts() {
            if (window.indexPageScripts) {
                return;
            }

            window.indexPageScripts = {};

            window.indexPageScripts.liveOrdersInterval = setInterval(() => {
                const element = document.getElementById('liveOrders');
                if (element) {
                    const current = parseInt(element.textContent.replace(/,/g, ''));
                    element.textContent = (current + Math.floor(Math.random() * 3) + 1).toLocaleString();
                }
            }, 5000);

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            window.indexPageScripts.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in-up');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('section > div').forEach(el => {
                window.indexPageScripts.observer.observe(el);
            });
        }

        function cleanupIndexPageScripts() {
            if (window.indexPageScripts) {
                if (window.indexPageScripts.liveOrdersInterval) {
                    clearInterval(window.indexPageScripts.liveOrdersInterval);
                }

                if (window.indexPageScripts.observer) {
                    window.indexPageScripts.observer.disconnect();
                }

                window.indexPageScripts = null;
            }
        }
    </script>
@endpush
