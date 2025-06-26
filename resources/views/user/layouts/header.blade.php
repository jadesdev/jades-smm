<header class="bg-white shadow-sm">
    <div class="px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
        <div class="flex items-center space-x-4">
            <!-- Currency Selector (Desktop) -->
            <div class="hidden md:flex items-center">
                <select
                    class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-white px-3 py-1">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                    <option value="NGN">NGN</option>
                </select>
            </div>

            <!-- Language Selector (Desktop) -->
            <div class="hidden md:flex items-center">
                <select
                    class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-white px-3 py-1">
                    <option value="en">EN</option>
                    <option value="es">ES</option>
                    <option value="fr">FR</option>
                    <option value="de">DE</option>
                </select>
            </div>

            <!-- Notifications -->
            <button
                class="relative p-2 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-bell text-lg"></i>
                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
            </button>

            <!-- User Profile Dropdown -->
            <div class="relative">
                <button id="user-menu-button" type="button"
                    class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 p-1">
                    <img class="h-8 w-8 rounded-full object-cover"
                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                        alt="Profile">
                </button>

                <!-- Dropdown Menu -->
                <div id="user-dropdown"
                    class="hidden absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                    <div class="px-4 py-2 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">John Doe</p>
                        <p class="text-xs text-gray-500">john@example.com</p>
                    </div>

                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-3 text-gray-400"></i>
                        Profile
                    </a>

                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-wallet mr-3 text-gray-400"></i>
                        Billing
                    </a>

                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-3 text-gray-400"></i>
                        Settings
                    </a>

                    <!-- Mobile Currency & Language -->
                    <div class="md:hidden border-t border-gray-200 mt-1">
                        <div class="px-4 py-2">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                Preferences</p>
                            <div class="space-y-2">
                                <select
                                    class="w-full text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="NGN">NGN</option>
                                </select>
                                <select
                                    class="w-full text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="en">English</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                    <option value="de">German</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 mt-1">
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
