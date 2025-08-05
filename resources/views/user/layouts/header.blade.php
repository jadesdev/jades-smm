@php
    $userNav = Auth::user();
@endphp
<header class="bg-white dark:bg-gray-700 shadow-sm">
    <div class="px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">@yield('title', 'Dashboard')</h1>
        <div class="flex items-center space-x-4">
            {{-- <!-- Currency Selector (Desktop) -->
            <div class="hidden md:flex items-center">
                <select
                    class="text-sm border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary-500 focus:border-primary-500 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-1">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                    <option value="NGN">NGN</option>
                </select>
            </div>

            <!-- Language Selector (Desktop) -->
            <div class="hidden md:flex items-center">
                <select
                    class="text-sm border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary-500 focus:border-primary-500 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-1">
                    <option value="en">EN</option>
                    <option value="es">ES</option>
                    <option value="fr">FR</option>
                    <option value="de">DE</option>
                </select>
            </div> --}}

            <!-- Dark Mode Toggle -->
            <button id="dark-mode-toggle"
                class="relative p-2 rounded-lg text-gray-400 dark:text-yellow-300 hover:text-gray-500 dark:hover:text-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-500 transition-colors">
                <i id="dark-mode-icon" class="fas fa-moon text-lg "></i>
            </button>
            {{-- <!-- Notifications -->
            <button
                class="relative p-2 rounded-full text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <i class="fad fa-bell text-lg"></i>
                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
            </button> --}}

            <!-- User Profile Dropdown -->
            <div class="relative">
                <button id="user-menu-button" type="button"
                    class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-primary-500 p-1">
                    <img class="h-8 w-8 rounded-full object-cover"
                        src="{{ $userNav->image? my_asset($userNav->image): my_asset('users/default.jpg') }}"
                        alt="Profile">
                </button>

                <!-- Dropdown Menu -->
                <div id="user-dropdown"
                    class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $userNav->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $userNav->email }}</p>
                    </div>

                    <a href="{{ route('user.profile') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fad fa-user mr-3 text-gray-400"></i>
                        Profile
                    </a>

                    <a href="{{ route('user.wallet') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fad fa-wallet mr-3 text-gray-400"></i>
                        Add Money
                    </a>

                    <a href="{{ route('user.support') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fad fa-envelope mr-3 text-gray-400"></i>
                        Support
                    </a>

                    {{-- <!-- Mobile Currency & Language -->
                    <div class="md:hidden border-t border-gray-200 dark:border-gray-700 mt-1">
                        <div class="px-4 py-2">
                            <p
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Preferences</p>
                            <div class="space-y-2">
                                <select
                                    class="w-full p-1 text-sm border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary-500 focus:border-primary-500 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="NGN">NGN</option>
                                </select>
                                <select
                                    class="w-full p-1 text-sm border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary-500 focus:border-primary-500 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="en">English</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                    <option value="de">German</option>
                                </select>
                            </div>
                        </div>
                    </div> --}}

                    <div class="border-t border-gray-200 dark:border-gray-700 mt-1">
                        <a href="{{ route('user.logout') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fad fa-sign-out-alt mr-3 text-gray-400"></i>
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
