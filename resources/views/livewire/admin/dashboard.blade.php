@section('title', $metaTitle)
<div>
    <div class="bg-gray-100 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Card: Total Users -->
            <a href="#"
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-green-500 to-green-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">Total Users</p>
                    <p class="mt-2 text-xl font-bold">5,6320,12</p>
                </div>
                <!-- Font Awesome Background Icon -->
                <i class="fa-solid fa-users absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- Card: User Balance -->
            <a href="#"
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-red-500 to-red-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">User Balance</p>
                    <p class="mt-2 text-xl font-bold">₦1.23M</p>
                </div>
                <i class="fa-solid fa-wallet absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- Card: Orders Today -->
            <a href="#"
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-teal-500 to-teal-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">Orders Today</p>
                    <p class="mt-2 text-xl font-bold">148</p>
                </div>
                <i class="fa-solid fa-cart-shopping absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- Card: Profits Today -->
            <a href="#"
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-yellow-400 to-yellow-600 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">Profits Today</p>
                    <p class="mt-2 text-xl font-bold">₦16,189</p>
                </div>
                <i class="fa-solid fa-sack-dollar absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

        </div>
    </div>

    <div class="bg-gray-100 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <a href="#"
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-green-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372a9.337 9.337 0 004.121-.952a4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.67c.12-.318.232-.656.328-1.014a6.375 6.375 0 011.056 4.67M4.5 15.06l.001.109A12.318 12.318 0 008.624 21c2.331 0 4.512-.645 6.374-1.766M4.5 15.06v-.003c0-1.113.285-2.16.786-3.07M4.5 15.06l.001-.109a6.375 6.375 0 01-1.056-4.67c-.12.318-.232.656-.328-1.014a6.375 6.375 0 01-11.964 4.67" />
                        </svg>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Total Users</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">5,632</p>
            </a>

            <a href="#"
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-blue-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 3a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 12m15 0a2.25 2.25 0 01-2.25 2.25H9a2.25 2.25 0 01-2.25-2.25m15 0a2.25 2.25 0 00-2.25-2.25H9a2.25 2.25 0 00-2.25 2.25" />
                        </svg>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">User Balance</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">₦1.23M</p>
            </a>

            <!-- Card: Orders Today -->
            <a href="#"
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-red-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.51 0 .962-.328 1.093-.828l2.857-9.591H3.881z" />
                        </svg>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Orders Today</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">148</p>
            </a>

            <!-- Card: Profits Today -->
            <a href="#"
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-yellow-400 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.826-1.106-2.303 0-3.128C10.444 7.219 11.232 7 12 7c.725 0 1.45.22 2.003.659" />
                        </svg>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Profits Today</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">₦16,189.07</p>
            </a>

            <!-- Add more cards by copying the structure -->

        </div>
    </div>
    <!-- This is the container for the new card designs -->
    <div class="bg-gray-100 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- Card: Total Users -->
            <div class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-xs">
                <!-- Icon Container -->
                <div class="p-3 mr-4 text-green-500 bg-green-100 dark:text-green-100 dark:bg-green-500 rounded-full">
                    <i class="fa-solid fa-users fa-xl"></i>
                </div>
                <!-- Text Content -->
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Total Users
                    </p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        5,632
                    </p>
                </div>
            </div>

            <!-- Card: User Balance -->
            <div class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-xs">
                <!-- Icon Container -->
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 dark:text-blue-100 dark:bg-blue-500 rounded-full">
                    <i class="fa-solid fa-wallet fa-xl"></i>
                </div>
                <!-- Text Content -->
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                        User Balance
                    </p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        ₦1.23M
                    </p>
                </div>
            </div>

            <!-- Card: Orders Today -->
            <div class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-xs">
                <!-- Icon Container -->
                <div
                    class="p-3 mr-4 text-orange-500 bg-orange-100 dark:text-orange-100 dark:bg-orange-500 rounded-full">
                    <i class="fa-solid fa-cart-shopping fa-xl"></i>
                </div>
                <!-- Text Content -->
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Orders Today
                    </p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        148
                    </p>
                </div>
            </div>

            <!-- Card: Profits Today -->
            <div class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-xs">
                <!-- Icon Container -->
                <div class="p-3 mr-4 text-teal-500 bg-teal-100 dark:text-teal-100 dark:bg-teal-500 rounded-full">
                    <i class="fa-solid fa-sack-dollar fa-xl"></i>
                </div>
                <!-- Text Content -->
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Profits Today
                    </p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        ₦16,189
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>



@include('layouts.meta')
