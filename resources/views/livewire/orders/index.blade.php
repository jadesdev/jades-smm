@section('title', $metaTitle)
<div wire:key="orders-index">
    <div
        class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">

        <x-button-group :attached="false" class="flex flex-wrap gap-1 md:gap-1.5">
            @foreach ($statuses as $statusItem)
                <x-button wire:click="updateStatus('{{ $statusItem }}')" :variant="$status === $statusItem ? 'primary' : 'ghost'" :outline="$status !== $statusItem"
                    class="{{ $status === $statusItem ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}">
                    {{ Str::title($statusItem) }}
                </x-button>
            @endforeach
        </x-button-group>

        <div class="w-full sm:w-auto sm:max-w-xs">
            <x-forms.input wire:model.live.debounce.500ms="search" name="search" type="search"
                placeholder="Search by Order ID..." class="!mb-0 md:w-64" />
        </div>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">Order
                        Details</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">Date
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">Status
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">Service
                    </th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">Charge
                    </th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">Start
                        Count</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        Quantity</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">Remains
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <!-- Sample Row 1 -->
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900 dark:text-white">#ORD-7892</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">instagram.com/target_user</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        2023-07-15 14:30
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-700 text-green-800 dark:text-green-300">
                            Completed
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        Instagram Followers (10k/day)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                        $12.80
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                        1,024
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                        5,000
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                        0
                    </td>
                </tr>

                <!-- Sample Row 2 -->
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900 dark:text-white">#ORD-8915</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">youtube.com/watch?v=abc123</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        2023-07-16 09:15
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-700 text-yellow-800 dark:text-yellow-300">
                            In Progress
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        YouTube Views (20k/day)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                        $8.50
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                        532
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                        10,000
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                        3,240
                    </td>
                </tr>

                <!-- Sample Row 3 -->
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900 dark:text-white">#ORD-6423</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">twitter.com/target_user</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        2023-07-17 16:45
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-300">
                            Canceled
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        Twitter Retweets
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                        $6.20
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                        0
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                        1,000
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                        1,000
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@include('layouts.meta')
