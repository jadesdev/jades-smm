@section('title', $metaTitle)
<div class="space-y-4">
    <div class="bg-gray-100 dark:bg-gray-900 p-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
            <!-- Total Users -->
            <a href="{{ route('admin.users') }}" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-green-500 to-green-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">Total Users</p>
                    <p class="mt-2 text-xl font-bold">{{ number_format($totalUsers) }}</p>
                </div>
                <i class="fa-solid fa-users absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- User Balance -->
            <a href="{{ route('admin.users') }}" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">User Balance</p>
                    <p class="mt-2 text-xl font-bold">{{ format_price($totalUserBalance) }}</p>
                </div>
                <i class="fa-solid fa-wallet absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- Referral Balance -->
            <a href="{{ route('admin.users') }}" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-teal-500 to-teal-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">Referral Balance</p>
                    <p class="mt-2 text-xl font-bold">{{ format_price($totalReferralBalance) }}</p>
                </div>
                <i class="fa-solid fa-sitemap absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- Deposits Today -->
            <a href="{{ route('admin.transactions.index') }}?serviceFilter=deposit" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-yellow-400 to-yellow-600 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">Deposits Today</p>
                    <p class="mt-2 text-xl font-bold">{{ format_price($depositsToday) }}</p>
                </div>
                <i class="fa-solid fa-arrow-down-to-bracket absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- Total Deposits -->
            <a href="{{ route('admin.transactions.index') }}?serviceFilter=deposit" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">Total Deposits</p>
                    <p class="mt-2 text-xl font-bold">
                        {{ format_price($totalDeposits, 2) }}</p>
                </div>
                <i class="fa-solid fa-building-columns absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- Profits Today -->
            <a href="#" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-yellow-400 to-yellow-600 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">Profits Today</p>
                    <p class="mt-2 text-xl font-bold">{{ format_price($profitsToday) }}</p>
                </div>
                <i class="fa-solid fa-hand-holding-dollar absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- 7 Days Profits -->
            <a href="#" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-green-500 to-green-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">7 Days Profits</p>
                    <p class="mt-2 text-xl font-bold">{{ format_price($profits7Days) }}</p>
                </div>
                <i class="fa-solid fa-chart-line absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- 30 Days Profits -->
            <a href="#" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-green-500 to-green-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">30 Days Profits</p>
                    <p class="mt-2 text-xl font-bold">
                        {{ format_price($profits30Days) }}</p>
                </div>
                <i class="fa-solid fa-calendar-day absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- All Profits -->
            <a href="#" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-green-500 to-green-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">All Profits</p>
                    <p class="mt-2 text-xl font-bold">
                        {{ format_price($allProfits) }}</p>
                </div>
                <i class="fa-solid fa-sack-dollar absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>

            <!-- Total Tickets -->
            <a href="{{ route('admin.support.tickets') }}" wire:navigate
                class="relative block p-6 rounded-xl text-white overflow-hidden bg-gradient-to-br from-purple-500 to-purple-700 transform hover:scale-105 transition-transform duration-300">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-white/80">Total Tickets</p>
                    <p class="mt-2 text-xl font-bold">{{ number_format($totalTickets) }}</p>
                </div>
                <i class="fa-solid fa-ticket absolute -right-4 -bottom-4 text-8xl text-white/20"></i>
            </a>
        </div>
    </div>
    {{-- Chart Section --}}
    <div
        class="bg-white dark:bg-gray-800 p-4 sm:p-6 lg:p-8 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Analytics</h3>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            <div class="xl:col-span-2">
                <div class="flex justify-between">
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Order Trend (Last 7 Days)</h4>
                    <x-forms.select wire:model.live.debounce.300ms="orderTrendPeriod" placeholder="Select Period">
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="90">Last 90 Days</option>
                        <option value="180">Last 180 Days</option>
                    </x-forms.select>
                </div>

                <div x-data="{
                    chart: null,
                    labels: @js($orderTrendLabels),
                    data: @js($orderTrendData),
                    init() {
                        this.$nextTick(() => {
                            this.renderChart();
                        }); 
                    },
                    renderChart() {
                        if (typeof ApexCharts === 'undefined') {
                            console.error('ApexCharts is not loaded');
                            return;
                        }
                
                        const isDark = document.documentElement.classList.contains('dark');
                
                        const options = {
                            chart: {
                                type: 'line',
                                height: 350,
                                toolbar: { show: false },
                                zoom: { enabled: false },
                                background: 'transparent'
                            },
                            series: this.data,
                            xaxis: {
                                categories: this.labels,
                                labels: {
                                    style: {
                                        colors: isDark ? '#9CA3AF' : '#6B7280'
                                    }
                                },
                                axisBorder: {
                                    color: isDark ? '#374151' : '#E5E7EB'
                                },
                                axisTicks: {
                                    color: isDark ? '#374151' : '#E5E7EB'
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: isDark ? '#9CA3AF' : '#6B7280'
                                    }
                                }
                            },
                            dataLabels: { enabled: false },
                            stroke: {
                                curve: 'smooth',
                                width: 3
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: isDark ? 'dark' : 'light',
                                    type: 'vertical',
                                    shadeIntensity: 0.9,
                                    gradientToColors: ['#10B981', '#F59E0B', '#3B82F6', '#EF4444', '#8B5CF6', '#14B8A6', '#F97316'],
                                    inverseColors: false,
                                    opacityFrom: 0.9,
                                    opacityTo: 0.5,
                                    stops: [0, 100]
                                }
                            },
                            colors: ['#10B981', '#F59E0B', '#3B82F6', '#EF4444', '#8B5CF6', '#14B8A6', '#F97316'],
                            grid: {
                                borderColor: isDark ? '#374151' : '#E5E7EB',
                                strokeDashArray: 3
                            },
                            tooltip: {
                                theme: isDark ? 'dark' : 'light'
                            },
                            markers: {
                                size: 4,
                                colors: ['#3B82F6'],
                                strokeColors: '#fff',
                                strokeWidth: 2,
                                hover: {
                                    size: 6
                                }
                            }
                        };
                
                        this.chart = new ApexCharts(this.$refs.lineChart, options);
                        this.chart.render();
                    }
                }" wire:ignore wire:key="{{ $orderTrendPeriod }}">
                    <div x-ref="lineChart" class="w-full"></div>
                </div>
            </div>

            <div class="xl:col-span-1">
                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Order Status Distribution</h4>
                <div x-data="{
                    chart: null,
                    labels: @js($orderStatusLabels),
                    data: @js($orderStatusData),
                
                    init() {
                        this.$nextTick(() => {
                            this.renderChart();
                        });
                    },
                
                    renderChart() {
                        if (typeof ApexCharts === 'undefined') {
                            console.error('ApexCharts is not loaded');
                            return;
                        }
                
                        const isDark = document.documentElement.classList.contains('dark');
                
                        const options = {
                            chart: {
                                type: 'pie',
                                height: 350,
                                background: 'transparent'
                            },
                            series: this.data,
                            labels: this.labels,
                            colors: ['#10B981', '#F59E0B', '#3B82F6', '#EF4444', '#8B5CF6', '#14B8A6'],
                            legend: {
                                position: 'bottom',
                                fontSize: '12px',
                                labels: {
                                    colors: isDark ? '#D1D5DB' : '#6B7280'
                                },
                                markers: {
                                    width: 8,
                                    height: 8
                                }
                            },
                            tooltip: {
                                theme: isDark ? 'dark' : 'light',
                                y: {
                                    formatter: function(val) {
                                        return val + ' orders'
                                    }
                                }
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '0%'
                                    }
                                }
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: '100%',
                                        height: 300
                                    },
                                    legend: {
                                        position: 'bottom',
                                        fontSize: '10px'
                                    }
                                }
                            }]
                        };
                
                        this.chart = new ApexCharts(this.$refs.pieChart, options);
                        this.chart.render();
                    }
                }" wire:ignore>
                    <div x-ref="pieChart" class="w-full"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- orders --}}
    <div class="bg-gray-100 dark:bg-gray-900 p-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
            <a href="{{ route('admin.orders.index') }}" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-blue-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-boxes-stacked h-6 w-6 text-blue-500"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Total Orders</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalOrders) }}</p>
            </a>

            <a href="{{ route('admin.orders.index') }}" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-yellow-400 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-cart-shopping h-6 w-6 text-yellow-400"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Orders Today</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($ordersToday) }}</p>
            </a>

            <a href="{{ route('admin.orders.index') }}?status=pending" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-orange-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-hourglass-half h-6 w-6 text-orange-500"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Pending Orders</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingOrders) }}
                </p>
            </a>

            <a href="{{ route('admin.orders.index') }}?status=processing" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-teal-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-spinner fa-spin h-6 w-6 text-teal-500"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Processing Orders</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($processingOrders) }}
                </p>
            </a>

            <a href="{{ route('admin.orders.index') }}?status=completed" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-green-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-check h-6 w-6 text-green-500"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Completed Orders</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($completedOrders) }}
                </p>
            </a>

            <a href="{{ route('admin.orders.index') }}?status=inprogress" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-teal-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-arrow-right h-6 w-6 text-teal-500"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">In Progress Orders</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($inProgressOrders) }}
                </p>
            </a>
            <a href="{{ route('admin.orders.index') }}?status=partial" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-blue-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-arrows-rotate h-6 w-6 text-blue-500"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Partial Orders</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($partialOrders) }}
                </p>
            </a>
            <a href="{{ route('admin.orders.index') }}?status=refunded" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-red-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-arrows-rotate h-6 w-6 text-red-500"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Refunded Orders</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($refundedOrders) }}
                </p>
            </a>

            <a href="{{ route('admin.orders.index') }}?status=canceled" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-red-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-ban h-6 w-6 text-red-500"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Cancelled Orders</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($canceledOrders) }}
                </p>
            </a>

            <a href="{{ route('admin.support.tickets') }}/open" wire:navigate
                class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-red-500 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-bell h-6 w-6 text-red-500"></i>
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium">Unread Tickets</h3>
                </div>
                <p class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($unreadTickets) }}
                </p>
            </a>
        </div>
    </div>
    {{-- recent orders --}}
    <div
        class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <h5 class="p-2 px-3 text-lg font-semibold dark:text-gray-300">Recent Orders</h5>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        ID
                    </th>
                    <th
                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        User
                    </th>
                    <th
                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        Order Details
                    </th>
                    <th
                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        Amount
                    </th>
                    <th
                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        Progress
                    </th>
                    <th
                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        Status
                    </th>
                    <th
                        class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        Date
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->id }}
                            @if ($order->error)
                                <i class="fas fa-exclamation-triangle text-red-500 ml-1" title="Has Error"></i>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <a href="{{ route('admin.users.view', $order->user_id) }}"
                                class="font-medium hover:text-primary-600 hover:underline transition-colors duration-150">
                                {{ $order->user?->name ?? 'N/A' }}
                            </a>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user?->email ?? '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if ($order->error_message)
                                <div class="text-xs text-red-600 mb-1" title="{{ $order->error_message }}">
                                    {{ Str::limit($order->error_message, 30) }}
                                </div>
                            @endif
                            <div class="font-medium text-gray-900 dark:text-white truncate max-w-xs mb-1">
                                {{ $order->service->name ?? 'Service not found' }}
                            </div>
                            <div class="text-xs text-gray-500 mb-1 dark:text-gray-400">
                                Type: {{ custom_text($order->service_type) }} |
                                Provider: {{ $order->provider?->name ?? 'Direct' }}
                            </div>
                            @if ($order->link)
                                <div class="text-gray-500 truncate max-w-xs dark:text-gray-400"
                                    title="{{ $order->link }}">
                                    <a href="{{ $order->link }}" target="_blank" rel="noopener noreferrer"
                                        class="hover:text-primary-500 transition-colors duration-150 text-xs">
                                        {{ Str::limit($order->link, 40) }}
                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="font-semibold">{{ format_price($order->price) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                API: {{ format_price($order->api_price) }}
                            </div>
                            <div class="text-xs text-green-600 dark:text-green-400">
                                Profit: {{ format_price($order->profit) }}
                            </div>
                            <div class="text-xs text-gray-500">Qty: {{ number_format($order->quantity) }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if ($order->remains > 0)
                                <div class="text-xs text-orange-600 dark:text-orange-400">
                                    {{ number_format($order->remains) }} remaining
                                </div>
                            @else
                                <div class="text-xs text-green-600 dark:text-green-400">
                                    Complete
                                </div>
                            @endif
                            @php
                                $completed = $order->quantity - $order->remains;
                                $progress = $order->quantity > 0 ? ($completed / $order->quantity) * 100 : 0;
                            @endphp
                            <div class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                    <div class="bg-primary-600 h-1.5 rounded-full transition-all duration-300"
                                        style="width: {{ min($progress, 100) }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ number_format($completed) }} / {{ number_format($order->quantity) }}
                                    ({{ number_format($progress, 1) }}%)
                                </div>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <x-order-status :status="$order->status" />
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $order->created_at->format('M j, Y') }}</div>
                            <div class="text-xs">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center space-y-3">
                                <i class="fa-solid fa-boxes-stacked fa-3x text-gray-300"></i>
                                <div>
                                    <p class="font-medium">No orders yet</p>
                                    <p class="text-sm">Orders will appear here once they are placed</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- recent users --}}
    <div
        class="bg-white dark:bg-gray-700 shadow overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <h5 class="p-2 px-3 text-lg font-semibold dark:text-gray-300">Recent Users</h5>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-gray-300">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Name
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Contact Info
                    </th>

                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Balance
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Joined
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if ($user->image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->image }}"
                                            alt="{{ $user->name }}">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center">
                                            <span
                                                class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->username }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                            @if ($user->phone)
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ format_price($user->balance) }}
                            </div>
                            @if ($user->bonus > 0)
                                <div class="text-sm text-green-600 dark:text-green-500">Bonus:
                                    {{ format_price($user->bonus) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-100' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <x-button href="{{ route('admin.users.view', $user->id) }}"
                                    size="xs">View</x-button>
                                {{-- <button class="text-red-600 hover:text-red-900">Delete</button> --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8"
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- best seller service --}}
    <div
        class="bg-white dark:bg-gray-700 shadow overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <h5 class="p-2 px-3 text-lg font-semibold dark:text-gray-300">Best Seller Service</h5>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-200 dark:bg-gray-800">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer">
                        ID
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer">
                        Name
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Total Orders
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Provider
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer">
                        Price
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer">
                        Type
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($services as $service)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $service->id }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace- text-sm font-medium text-gray-900 max-w-sm dark:text-gray-100">
                            {{ textTrim($service->name) }}
                        </td>
                        <td class="px-6 py-4 whitespace- text-sm text-gray-500 dark:text-gray-400">
                            {{ number_format($service->orders_count) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            <p>{{ $service->api_service_id }}</p>
                            {{ $service->provider?->name ?? 'Manual' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ format_price($service->price, 2) }}
                            <p>{{ format_price($service->original_price) }}</p>
                            {{ $service->provider?->currency ?? '$' }}{{ number_format($service->api_price, 2) }}

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $service->type }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9"
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                            No services found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('layouts.meta')
