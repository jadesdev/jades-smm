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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 px-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Balance</p>
                    <p class="text-md font-bold text-gray-900 dark:text-gray-100">{{ format_price($userBalance) }}</p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-800 p-1.5 px-2 rounded-full">
                    <i class="fad fa-wallet text-emerald-600 dark:text-emerald-100 text-md"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 px-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                    <p class="text-md font-bold text-gray-900 dark:text-gray-100">{{ $totalOrders }}</p>
                </div>
                <div class="bg-sky-100 dark:bg-sky-800 p-1.5 px-2 rounded-full">
                    <i class="fad fa-box text-sky-600 dark:text-sky-100 text-md"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 px-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Spent</p>
                    <p class="text-md font-bold text-gray-900 dark:text-gray-100">{{ format_price($totalSpent) }}</p>
                </div>
                <div class="bg-rose-100 dark:bg-rose-800 p-1.5 px-2 rounded-full">
                    <i class="fad fa-coins text-rose-600 dark:text-rose-100 text-md"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 px-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tickets</p>
                    <p class="text-md font-bold text-gray-900 dark:text-gray-100">{{ $totalTickets }}</p>
                </div>
                <div class="bg-indigo-100 dark:bg-indigo-800 p-1.5 px-2 rounded-full">
                    <i class="fad fa-life-ring text-indigo-600 dark:text-indigo-100 text-md"></i>
                </div>
            </div>
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
