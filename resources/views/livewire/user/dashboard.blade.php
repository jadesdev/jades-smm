@section('title', $metaTitle)
<div>
    <!-- Welcome Section -->
    <div class="mb-8">
        <div
            class="bg-gradient-to-r from-primary-600 to-purple-600 rounded-lg p-6 text-white dark:bg-gradient-to-r dark:from-primary-800 dark:to-purple-800">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
                    <p class="text-primary-100 dark:text-primary-300">{{ $randomQuotes }}</p>
                </div>
                <div class="hidden md:block">
                    <i class="fad fa-chart-line text-4xl text-primary-200 dark:text-primary-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
        <div
            class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-green-500 to-green-700 transform hover:scale-105 transition-transform duration-300">
            <div class="relative z-10">
                <p class="text-sm font-medium text-white/80">Balance</p>
                <p class="mt-2 text-xl font-bold">{{ format_price($userBalance) }}</p>
            </div>
            <i class="fa-solid fa-wallet absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
        </div>

        <div
            class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-primary-500 to-primary-700 transform hover:scale-105 transition-transform duration-300">
            <div class="relative z-10">
                <p class="text-sm font-medium text-white/80">Total Orders</p>
                <p class="mt-2 text-xl font-bold">{{ $totalOrders }}</p>
            </div>
            <i class="fa-solid fa-cart-shopping absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
        </div>
        <div
            class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-rose-500 to-rose-700 transform hover:scale-105 transition-transform duration-300">
            <div class="relative z-10">
                <p class="text-sm font-medium text-white/80">Total Spent</p>
                <p class="mt-2 text-xl font-bold">{{ format_price($totalSpent) }}</p>
            </div>
            <i class="fa-solid fa-money-bill absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
        </div>
        <div
            class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-700 transform hover:scale-105 transition-transform duration-300">
            <div class="relative z-10">
                <p class="text-sm font-medium text-white/80">Total Tickets</p>
                <p class="mt-2 text-xl font-bold">{{ $totalTickets }}</p>
            </div>
            <i class="fa-solid fa-headset absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
        </div>

    </div>

    <h4 class="text-lg font-semibold my-2 dark:text-white">Quick Actions</h4>
    <div class="flex overflow-x-auto space-x-4 p-2 w-full mb-4">
        <!-- New Order -->
        <div
            class="min-w-[150px] bg-white dark:bg-gray-800 text-center rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5 flex-shrink-0">
            <a href="{{ route('user.orders.create') }}" class="flex flex-col items-center">
                <i class="fa fa-cart-shopping text-xl text-orange-500"></i>
                <h5 class="mt-2 text-xs font-medium dark:text-white">New Order</h5>
            </a>
        </div>

        <!-- Add Funds -->
        <div
            class="min-w-[150px] bg-white dark:bg-gray-800 text-center rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5 flex-shrink-0">
            <a href="{{ route('user.wallet') }}" class="flex flex-col items-center">
                <i class="fa fa-wallet text-xl text-green-500"></i>
                <h5 class="mt-2 text-xs font-medium dark:text-white">Add Funds</h5>
            </a>
        </div>

        <div
            class="min-w-[150px] bg-white dark:bg-gray-800 text-center rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5 flex-shrink-0">
            <a href="{{ route('user.support.create') }}" class="flex flex-col items-center">
                <i class="fa fa-headset text-xl text-purple-500"></i>
                <h5 class="mt-2 text-xs font-medium dark:text-white">Support</h5>
            </a>
        </div>
        <!-- Refer & Earn -->
        <div
            class="min-w-[150px] bg-white dark:bg-gray-800 text-center rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5 flex-shrink-0">
            <a href="{{ route('user.referrals') }}" class="flex flex-col items-center">
                <i class="fa fa-gift text-xl text-primary-500"></i>
                <h5 class="mt-2 text-xs font-medium dark:text-white">Referrals</h5>
            </a>
        </div>

        <!-- Transactions -->
        <div
            class="min-w-[150px] bg-white dark:bg-gray-800 text-center rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5 flex-shrink-0">
            <a href="{{ route('user.wallet') }}?tab=transactions" class="flex flex-col items-center">
                <i class="fa fa-exchange text-xl text-rose-500"></i>
                <h5 class="mt-2 text-xs font-medium dark:text-white">Transactions</h5>
            </a>
        </div>

    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recent Activity</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach ($recentActivities as $activity)
                    <div class="flex items-center space-x-4">
                        <div class="bg-primary-100 p-2 rounded-full">
                            <img src="{{ static_asset('services/' . $activity->image) }}" alt=""
                                class="w-6 h-6 rounded-full">
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $activity->message }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($activity->service) }} -
                                {{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('layouts.meta')
