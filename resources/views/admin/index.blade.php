@extends('admin.layouts.main')
@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-primary-600 to-purple-600 rounded-lg p-6 text-white dark:bg-gradient-to-r dark:from-primary-800 dark:to-purple-800">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Welcome back, John!</h2>
                    <p class="text-primary-100 dark:text-primary-300">Here's what's happening with your SMM campaigns
                        today.</p>
                </div>
                <div class="hidden md:block">
                    <i class="fad fa-chart-line text-4xl text-primary-200 dark:text-primary-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">1,234</p>
                </div>
                <div class="bg-primary-100 dark:bg-primary-800 p-3 rounded-full">
                    <i class="fad fa-shopping-cart text-primary-600 dark:text-primary-100"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Services</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">89</p>
                </div>
                <div class="bg-green-100 dark:bg-green-800 p-3 rounded-full">
                    <i class="fad fa-cogs text-green-600 dark:text-green-100"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Spent</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">$5,678</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-800 p-3 rounded-full">
                    <i class="fad fa-dollar-sign text-yellow-600 dark:text-yellow-100"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Success Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">96%</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-800 p-3 rounded-full">
                    <i class="fad fa-chart-line text-purple-600 dark:text-purple-100"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recent Activity</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 dark:bg-green-800 p-2 rounded-full">
                        <i class="fad fa-check text-green-600 dark:text-green-100"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Order #1234 completed</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Instagram followers - 2 hours ago</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 dark:bg-blue-800 p-2 rounded-full">
                        <i class="fad fa-clock text-blue-600 dark:text-blue-100"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Order #1235 in progress</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">YouTube views - 4 hours ago</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="bg-yellow-100 dark:bg-yellow-800 p-2 rounded-full">
                        <i class="fad fa-exclamation-triangle text-yellow-600 dark:text-yellow-100"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Payment pending</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Order #1236 - 6 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
