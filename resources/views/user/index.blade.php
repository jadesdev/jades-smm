@extends('user.layouts.main')
@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Welcome back, John!</h2>
                    <p class="text-indigo-100">Here's what's happening with your SMM campaigns
                        today.</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chart-line text-4xl text-indigo-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">1,234</p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-full">
                    <i class="fas fa-shopping-cart text-indigo-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Services</p>
                    <p class="text-2xl font-bold text-gray-900">89</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-cogs text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Spent</p>
                    <p class="text-2xl font-bold text-gray-900">$5,678</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Success Rate</p>
                    <p class="text-2xl font-bold text-gray-900">96%</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 p-2 rounded-full">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Order #1234 completed</p>
                        <p class="text-xs text-gray-500">Instagram followers - 2 hours ago</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Order #1235 in progress</p>
                        <p class="text-xs text-gray-500">YouTube views - 4 hours ago</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="bg-yellow-100 p-2 rounded-full">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Payment pending</p>
                        <p class="text-xs text-gray-500">Order #1236 - 6 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
