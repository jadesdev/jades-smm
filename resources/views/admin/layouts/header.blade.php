<header class="bg-white dark:bg-gray-700 shadow-sm">
    <div class="px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <button id="mobile-menu-button"
            class="flex lg:hidden flex-col items-center py-2 px-3 text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
            <i class="fad fa-bars text-lg"></i>
        </button>
        {{-- <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">@yield('title', 'Dashboard')</h1> --}}
        <h3></h3>
        <div class="flex items-center space-x-4">
            <!-- Currency Selector (Desktop) -->
            {{-- <div class="hidden md:flex items-center">
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
                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                        alt="Profile">
                </button>

                @php
                    $adminSidebar = Auth::guard('admin')->user();
                @endphp
                <div id="user-dropdown"
                    class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $adminSidebar->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $adminSidebar->email }}</p>
                    </div>

                    <a href="{{ route('admin.profile') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fad fa-user mr-3 text-gray-400"></i>
                        Profile
                    </a>

                    <a href="{{ route('admin.support.tickets') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fad fa-envelope mr-3 text-gray-400"></i>
                        Support
                    </a>

                    {{-- <div class="md:hidden border-t border-gray-200 dark:border-gray-700 mt-1">
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
                        <a href="{{ route('admin.logout') }}"
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
