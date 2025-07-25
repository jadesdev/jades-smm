@section('title', $metaTitle)
<div wire:key="orders-index">
    <div
        class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">

        <x-button-group :attached="false" class="flex flex-wrap gap-1 md:gap-1.5">
            @foreach ($statuses as $statusItem)
                <x-button wire:click="updateStatus('{{ $statusItem }}')" :variant="$status === $statusItem ? 'primary' : 'primary'" :outline="$status !== $statusItem"
                    class="{{ $status === $statusItem ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}">
                    {{ Str::title($statusItem) }}
                    @if ($statusItem !== 'all')
                        <span class="ml-1 text-xs opacity-75">({{ $statusCounts[$statusItem] ?? 0 }})</span>
                    @endif
                </x-button>
            @endforeach
        </x-button-group>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="w-full sm:w-auto sm:max-w-xs">
                <x-forms.input wire:model.live.debounce.500ms="search" name="search" type="search"
                    placeholder="Search by Order ID, Service..." class="!mb-0 md:w-64" />
            </div>
            @if ($search || $status !== 'all')
                <x-button wire:click="clearFilters" variant="primary" outline size="sm" class="whitespace-nowrap mb-4">
                    Clear
                </x-button>
            @endif
        </div>
    </div>

    <div class="mb-4 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
        <div>
            Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }}
            of {{ $orders->total() }} orders
        </div>
        <div class="flex items-center gap-2">
            <label for="per-page" class="text-sm">Per page:</label>
            <select wire:model.live="perPage" id="per-page"
                class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-sm bg-white dark:bg-gray-800">
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <div
        class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        ID
                    </th>
                    <th
                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                        Service & Link
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
                            #{{ $order->id }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900 dark:text-white truncate max-w-xs mb-1">
                                {{ $order->service->name ?? 'Service not found' }}
                            </div>
                            <div class="text-gray-500 truncate max-w-xs" title="{{ $order->link }}">
                                <a href="{{ $order->link }}" target="_blank" rel="noopener noreferrer"
                                    class="hover:text-primary-500 transition-colors duration-150">
                                    {{ Str::limit($order->link, 40) }}
                                    <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            <div class="font-semibold">{{ format_price($order->price) }}</div>
                            <div class="text-xs text-gray-500">Qty: {{ number_format($order->quantity) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $completed = $order->start_counter + ($order->quantity - $order->remains);
                                $progress = $order->quantity > 0 ? ($completed / $order->quantity) * 100 : 0;
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ min($progress, 100) }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ number_format($completed) }} / {{ number_format($order->quantity) }}
                                ({{ number_format($progress, 1) }}%)
                            </div>
                            @if ($order->remains > 0)
                                <div class="text-xs text-orange-600 dark:text-orange-400">
                                    {{ number_format($order->remains) }} remaining
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <x-order-status :status="$order->status" />
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $order->created_at->format('M j, Y') }}</div>
                            <div class="text-xs">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center space-y-3">
                                <i class="fa-solid fa-boxes-stacked fa-3x text-gray-300"></i>
                                <div>
                                    @if ($search)
                                        <p class="font-medium">No orders match your search</p>
                                        <p class="text-sm">Try adjusting your search terms or filters</p>
                                    @elseif ($status !== 'all')
                                        <p class="font-medium">No {{ $status }} orders found</p>
                                        <p class="text-sm">Try selecting a different status</p>
                                    @else
                                        <p class="font-medium">No orders yet</p>
                                        <p class="text-sm">Your orders will appear here once you place them</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@include('layouts.meta')
