@section('title', $metaTitle)
<div>
    <div class="flex justify-between">
        <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Statistics</h5>
        <div class="space-x-2">
            <x-button variant="primary" :outline="$tab != 'provider'" wire:click="changeTab('provider')" size="sm">
                Provider
            </x-button>
            <x-button variant="primary" :outline="$tab != 'user'" wire:click="changeTab('user')" size="sm">
                User
            </x-button>
            <x-button variant="primary" :outline="$tab != 'service'" wire:click="changeTab('service')" size="sm">
                Service
            </x-button>
            <x-button variant="primary" :outline="$tab != 'order'" wire:click="changeTab('order')" size="sm">
                Order
            </x-button>
        </div>

    </div>


    @if ($tab == 'provider')
        <x-card>
            <x-slot name="header">
                <div class="flex justify-between">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-gray-100 my-auto">Provider Statistics</h6>
                    <x-forms.select wire:model.live.debounce.300ms="duration" class="!mb-0">
                        @foreach ($durationList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
            </x-slot>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Provider
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Sales Today
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Sales {{ $durationList[$duration] }}
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Profit Today
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Profit {{ $durationList[$duration] }}
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Orders Today
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Orders {{ $durationList[$duration] }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($providerStats as $stat)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $stat['provider']->name ?? 'Unknown Provider' }}
                                            </div>
                                            @if ($stat['provider']->url)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ parse_url($stat['provider']->url, PHP_URL_HOST) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 font-medium">
                                        {{ format_price($stat['sales_today']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 font-medium">
                                        {{ format_price($stat['sales_period']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm font-medium">
                                        @if ($stat['profit_today'] > 0)
                                            <span class="text-green-600 dark:text-green-400">
                                                {{ format_price($stat['profit_today']) }}
                                            </span>
                                        @elseif($stat['profit_today'] < 0)
                                            <span class="text-red-600 dark:text-red-400">
                                                -{{ format_price(abs($stat['profit_today'])) }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">
                                                $0.00
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm font-medium">
                                        @if ($stat['profit_period'] > 0)
                                            <span class="text-green-600 dark:text-green-400">
                                                {{ format_price($stat['profit_period']) }}
                                            </span>
                                        @elseif($stat['profit_period'] < 0)
                                            <span class="text-red-600 dark:text-red-400">
                                                -{{ format_price(abs($stat['profit_period'])) }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">
                                                {{ format_price(0) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($stat['orders_today']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($stat['orders_period']) }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm">No provider statistics available</p>
                                        <p class="text-xs text-gray-400 mt-1">Add some orders to see provider
                                            performance</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (count($providerStats) > 0)
                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ count($providerStats) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total Providers</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ format_price(collect($providerStats)->sum('sales_today'), 2) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Sales Today</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ format_price(collect($providerStats)->sum('sales_period'), 2) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Sales {{ $durationList[$duration] }}
                            </div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ number_format(collect($providerStats)->sum('orders_period')) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Orders {{ $durationList[$duration] }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </x-card>
    @elseif ($tab == 'user')
        <x-card>
            <x-slot name="header">
                <div class="flex justify-between">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-gray-100 my-auto">User Statistics</h6>
                    <x-forms.select wire:model.live.debounce.300ms="duration" class="!mb-0">
                        @foreach ($durationList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
            </x-slot>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                User
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total Stats
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Current Balance
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Deposits {{ $durationList[$duration] }}
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Spent {{ $durationList[$duration] }}
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Orders {{ $durationList[$duration] }}
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Lifetime Orders
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($userStats as $stat)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <a href="{{ route('admin.users.view', $stat['user']->id) }}"
                                                    class="hover:underline">
                                                    {{ $stat['user']->name ?? $stat['user']->username }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $stat['user']->email }}
                                            </div>
                                            @if ($stat['first_order_date'])
                                                <div class="text-xs text-gray-400">
                                                    Customer since
                                                    {{ \Carbon\Carbon::parse($stat['first_order_date'])->format('M Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-green-600 dark:text-green-400 font-medium">
                                        Deposits: {{ format_price($stat['total_deposits']) }}
                                    </div>
                                    <div class="text-sm text-red-600 dark:text-red-400 font-medium">
                                        Spent: {{ format_price($stat['total_spent']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm font-medium">
                                        @if ($stat['current_balance'] > 0)
                                            <span class="text-green-600 dark:text-green-400">
                                                {{ format_price($stat['current_balance']) }}
                                            </span>
                                        @elseif($stat['current_balance'] < 0)
                                            <span class="text-red-600 dark:text-red-400">
                                                -{{ format_price(abs($stat['current_balance'])) }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">
                                                {{ format_price(0) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                        {{ format_price($stat['deposits_period']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                        {{ format_price($stat['spent_period']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($stat['orders_period']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ number_format($stat['lifetime_orders']) }}
                                        </div>
                                        @if ($stat['first_order_date'])
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Since
                                                {{ \Carbon\Carbon::parse($stat['first_order_date'])->format('M Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-2 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                            </path>
                                        </svg>
                                        <p class="text-sm">No user statistics available</p>
                                        <p class="text-xs text-gray-400 mt-1">Users with orders will appear here</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (count($userStats) > 0)
                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ count($userStats) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Top Users</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ format_price(collect($userStats)->sum('total_deposits')) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total Deposits</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ format_price(collect($userStats)->sum('total_spent')) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total Spent</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ number_format(collect($userStats)->sum('lifetime_orders')) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total Orders</div>
                        </div>
                    </div>
                </div>
            @endif
        </x-card>
    @elseif ($tab == 'service')
        <x-card>
            <x-slot name="header">
                <div class="flex justify-between">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-gray-100 my-auto">Service Statistics</h6>
                    <x-forms.select wire:model.live.debounce.300ms="duration" class="!mb-0">
                        @foreach ($durationList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
            </x-slot>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Service
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Provider
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Orders {{ $durationList[$duration] }}
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Sales {{ $durationList[$duration] }}
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Profit {{ $durationList[$duration] }}
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Lifetime Orders
                            </th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($serviceStats as $stat)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div
                                        class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate max-w-sm">
                                        {{ $stat['service']->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $stat['service']->category->name ?? 'Uncategorized' }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $stat['service']->provider->name ?? 'Manual' }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($stat['orders_period']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 font-medium">
                                        {{ format_price($stat['sales_period']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm font-medium">
                                        @if ($stat['profit_period'] > 0)
                                            <span class="text-green-600 dark:text-green-400">
                                                {{ format_price($stat['profit_period']) }}
                                            </span>
                                        @elseif($stat['profit_period'] < 0)
                                            <span class="text-red-600 dark:text-red-400">
                                                -{{ format_price(abs($stat['profit_period'])) }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">
                                                {{ format_price(0) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($stat['lifetime_orders']) }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    @if ($stat['service']->status)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                            Disabled
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-2 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm">No service statistics available</p>
                                        <p class="text-xs text-gray-400 mt-1">Orders placed for services will appear
                                            here</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (count($serviceStats) > 0)
                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ count($serviceStats) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Top Services</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ format_price(collect($serviceStats)->sum('sales_period')) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Sales {{ $durationList[$duration] }}
                            </div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold">
                                @php
                                    $totalProfit = collect($serviceStats)->sum('profit_period');
                                @endphp
                                <span
                                    class="{{ $totalProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ format_price($totalProfit) }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Profit
                                {{ $durationList[$duration] }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ number_format(collect($serviceStats)->sum('orders_period')) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Orders
                                {{ $durationList[$duration] }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </x-card>
    @elseif ($tab == 'order')
        <x-card>
            <x-slot name="header">
                <div class="flex justify-between">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-gray-100 my-auto">Order Statistics</h6>
                    <x-forms.select wire:model.live.debounce.300ms="duration" class="!mb-0">
                        @foreach ($durationList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
            </x-slot>

            @if (!empty($orderStats) && $orderStats['kpi']['total_orders'] > 0)
                <div>
                    <!-- KPI Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                                {{ format_price($orderStats['kpi']['total_revenue']) }}
                            </div>
                        </div>
                        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Profit</div>
                            <div
                                class="text-2xl font-bold {{ $orderStats['kpi']['total_profit'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mt-1">
                                {{ format_price($orderStats['kpi']['total_profit']) }}
                            </div>
                        </div>
                        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Orders</div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                                {{ number_format($orderStats['kpi']['total_orders']) }}
                            </div>
                        </div>
                        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Avg. Order Value</div>
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                                {{ format_price($orderStats['kpi']['avg_order_value']) }}
                            </div>
                        </div>
                    </div>

                    <!-- Breakdown Cards -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Status Distribution -->
                        <div class="p-4 border dark:border-gray-700 rounded-lg">
                            <h6 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Order Status Distribution
                            </h6>
                            <div class="space-y-2 text-sm">
                                @forelse($orderStats['status_distribution'] as $status => $count)
                                    <div class="flex justify-between items-center">
                                        <span
                                            class="capitalize text-gray-600 dark:text-gray-300">{{ str_replace('_', ' ', $status) }}</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-100">{{ number_format($count) }}</span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center col-span-full">No status data available.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Order Type Breakdown -->
                        <div class="p-4 border dark:border-gray-700 rounded-lg">
                            <h6 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Order Type Breakdown</h6>
                            <div class="space-y-2 text-sm">
                                @forelse($orderStats['type_distribution'] as $type => $count)
                                    <div class="flex justify-between items-center">
                                        <span
                                            class="capitalize text-gray-600 dark:text-gray-300">{{ str_replace('_', ' ', $type) }}</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-100">{{ number_format($count) }}</span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center col-span-full">No order type data available.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Busiest Hours -->
                        <div class="p-4 border dark:border-gray-700 rounded-lg">
                            <h6 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Busiest Hours (Top 5)</h6>
                            <div class="space-y-2 text-sm">
                                @forelse($orderStats['busiest_hours'] as $hour => $count)
                                    <div class="flex justify-between items-center">
                                        <span
                                            class="text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::createFromFormat('H', $hour)->format('g A') }}</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-100">{{ number_format($count) }}
                                            orders</span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center col-span-full">Not enough data for hourly
                                        trends.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Top Performing Days -->
                        <div class="p-4 border dark:border-gray-700 rounded-lg col-span-1 md:col-span-2 lg:col-span-3">
                            <h6 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Top Performing Days (Top 5)
                            </h6>
                            <div class="space-y-2 text-sm">
                                @forelse($orderStats['top_days'] as $date => $day)
                                    <div class="grid grid-cols-3 items-center">
                                        <span
                                            class="text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-100">{{ number_format($day['count']) }}
                                            orders</span>
                                        <span
                                            class="font-semibold text-green-600 dark:text-green-400 text-right">{{ format_price($day['revenue']) }}</span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center col-span-full">Not enough data for daily
                                        trends.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        <p class="text-sm">No order statistics available for this period</p>
                        <p class="text-xs text-gray-400 mt-1">Place some orders to see statistics here</p>
                    </div>
                </div>
            @endif
        </x-card>
    @endif
</div>



@include('layouts.meta')
