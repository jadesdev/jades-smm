@php
    $footerSetting = get_setting();
@endphp
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
        <div
            class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 border-t border-gray-200 dark:border-gray-700 pt-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">&copy; {{ date('Y') }}
                {{ $footerSetting->name ?? 'Jades SMM' }}. All rights reserved.</p>

            <div class="flex items-center space-x-6 text-sm">
                <a href="{{ route('how-it-works') }}"
                    class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    How It Works
                </a>
                <a href="{{ route('terms') }}"
                    class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    Terms
                </a>
            </div>

            <div class="flex items-center space-x-4">
                @if ($footerSetting->twitter)
                    <a href="{{ $footerSetting->twitter }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <i class="fab fa-x text-2xl"></i>
                    </a>
                @endif
                @if ($footerSetting->facebook)
                    <a href="{{ $footerSetting->facebook }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <i class="fab fa-facebook-f text-2xl"></i>
                    </a>
                @endif
                @if ($footerSetting->instagram)
                    <a href="{{ $footerSetting->instagram }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                @endif
                @if ($footerSetting->telegram)
                    <a href="{{ $footerSetting->telegram }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <i class="fab fa-telegram text-2xl"></i>
                    </a>
                @endif
                @if ($footerSetting->whatsapp)
                    <a href="{{ $footerSetting->whatsapp }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <i class="fab fa-whatsapp text-2xl"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</footer>
