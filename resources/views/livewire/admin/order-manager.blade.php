@section('title', $metaTitle)

<div wire:key="orders-index">
    @if ($page === 'list')
        <!-- Filters Section -->
        <div
            class="flex flex-col gap-4 p-4 mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">

            <!-- Status Filter -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
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
                            placeholder="Search orders..." class="!mb-0 md:w-64" />
                    </div>
                    @if ($search || $status !== 'all' || $provider !== 'all' || $serviceType !== 'all')
                        <x-button wire:click="clearFilters" variant="danger" outline size="sm"
                            class="whitespace-nowrap mb-4">
                            Clear
                        </x-button>
                    @endif
                </div>
            </div>

            <!-- Additional Filters -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Provider Filter -->
                <div class="flex-1">
                    <x-forms.select wire:model.live="provider" label="Provider" name="provider" class="!mb-0"
                        placeholder="All Providers">
                        @foreach ($providers as $providerItem)
                            <option value="{{ $providerItem['id'] }}">{{ $providerItem['name'] }}</option>
                        @endforeach
                    </x-forms.select>
                </div>

                <!-- Service Type Filter -->
                <div class="flex-1">
                    <x-forms.select wire:model.live="serviceType" label="Service Type" name="serviceType" class="!mb-0"
                        placeholder="All Service Types">
                        @foreach ($serviceTypes as $serviceTypeItem)
                            <option value="{{ $serviceTypeItem }}">{{ custom_text($serviceTypeItem) }}</option>
                        @endforeach
                    </x-forms.select>
                </div>

                <!-- Per Page -->
                <div class="flex-1">
                    <x-forms.select wire:model.live="perPage" label="Per Page" name="perPage" class="!mb-0"
                        :options="[25 => '25', 50 => '50', 100 => '100']" />
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        @if (count($selectedOrders) > 0)
            <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        {{ count($selectedOrders) }} order(s) selected
                    </span>
                    <div class="flex flex-wrap gap-2">
                        <x-button wire:click="bulkSetStatus('pending')" size="sm" variant="primary" outline>
                            Set to Pending
                        </x-button>
                        <x-button wire:click="bulkSetStatus('inprogress')" size="sm" variant="primary" outline>
                            Set to In Progress
                        </x-button>
                        <x-button wire:click="bulkSetStatus('completed')" size="sm" variant="success" outline>
                            Set to Completed
                        </x-button>
                        <x-button wire:click="bulkResend" size="sm" variant="warning" outline>
                            Resend Selected
                        </x-button>
                        <x-button wire:click="bulkCancelAndRefund" size="sm" variant="danger" outline>
                            Cancel & Refund
                        </x-button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Results Info -->
        <div class="mb-4 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
            <div>
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }}
                of {{ $orders->total() }} orders
            </div>
        </div>

        <!-- Orders Table -->
        <div
            class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        </th>
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
                        <th
                            class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-white uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <!-- Checkbox -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" wire:model.live="selectedOrders" value="{{ $order->id }}"
                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </td>

                            <!-- ID -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $order->id }}
                                @if ($order->error)
                                    <i class="fas fa-exclamation-triangle text-red-500 ml-1" title="Has Error"></i>
                                @endif
                            </td>

                            <!-- User -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <a href="{{ route('admin.users.view', $order->user_id) }}"
                                    class="font-medium hover:text-primary-600 hover:underline transition-colors duration-150">
                                    {{ $order->user?->name ?? 'N/A' }}
                                </a>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user?->email ?? '' }}
                                </div>
                            </td>

                            <!-- Order Details -->
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
                                    Provider: {{ $order->apiProvider?->name ?? 'Direct' }}
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

                            <!-- Amount -->
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

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- <x-button wire:click="viewOrder({{ $order->id }})" variant="primary" outline
                                    size="xs" class="rounded">
                                    View
                                </x-button> --}}

                                    <x-dropdown.menu align="right" width="md" position="bottom">
                                        <x-slot name="trigger">
                                            <x-button variant="primary" outline size="xs" class="rounded px-2">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </x-button>
                                        </x-slot>

                                        <x-dropdown.item wire:click="editOrder({{ $order->id }})">
                                            <i class="fa fa-edit fa-fw mr-2"></i> Edit Order
                                        </x-dropdown.item>

                                        @if ($order->response)
                                            <x-dropdown.item wire:click="viewResponse({{ $order->id }})">
                                                <i class="fa fa-eye fa-fw mr-2"></i> View Response
                                            </x-dropdown.item>
                                        @endif

                                        @if ($order->error == 1 || in_array($order->status, ['error', 'fail']))
                                            <x-dropdown.item wire:click="resendOrder({{ $order->id }})">
                                                <i class="fa fa-redo fa-fw mr-2"></i> Resend Order
                                            </x-dropdown.item>
                                        @endif

                                        <x-dropdown.divider />

                                        <x-dropdown.item wire:click="deleteModal({{ $order->id }})"
                                            class="text-red-600" variant="danger">
                                            <i class="fa fa-trash-alt fa-fw mr-2"></i> Delete
                                        </x-dropdown.item>
                                    </x-dropdown.menu>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center space-y-3">
                                    <i class="fa-solid fa-boxes-stacked fa-3x text-gray-300"></i>
                                    <div>
                                        @if ($search)
                                            <p class="font-medium">No orders match your search</p>
                                            <p class="text-sm">Try adjusting your search terms or filters</p>
                                        @elseif ($status !== 'all' || $provider !== 'all' || $serviceType !== 'all')
                                            <p class="font-medium">No orders found with current filters</p>
                                            <p class="text-sm">Try adjusting your filters</p>
                                        @else
                                            <p class="font-medium">No orders yet</p>
                                            <p class="text-sm">Orders will appear here once they are placed</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($orders->hasPages())
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <x-card>
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-lg">Edit Order #{{ $editingOrder?->id }}</h3>
                    <x-button wire:click="cancelEdit" variant="secondary">Back</x-button>
                </div>
            </x-slot>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column - Read Only Info -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Order Information</h4>

                        <!-- Category -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                            <input type="text" value="{{ $editingOrder?->service?->category?->name ?? 'N/A' }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                                disabled>
                        </div>

                        <!-- Service -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service</label>
                            <input type="text" value="{{ $editingOrder?->service?->name ?? 'N/A' }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                                disabled>
                        </div>

                        <!-- Quantity & Original Start Counter -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                                <input type="number" value="{{ $editingOrder?->quantity }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                                    disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Original
                                    Start Counter</label>
                                <input type="number" value="{{ $editingOrder?->start_counter }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                                    disabled>
                            </div>
                        </div>

                        <!-- API Provider & API Order ID -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API
                                    Provider</label>
                                <input type="text" value="{{ $editingOrder?->apiProvider?->name ?? 'Direct' }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                                    disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API
                                    Order ID</label>
                                <input type="text" value="{{ $editingOrder?->api_order_id ?? 'N/A' }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                                    disabled>
                            </div>
                        </div>

                        <!-- API Response -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API
                                Response</label>
                            <textarea rows="4"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                                disabled>{{ $editingOrder?->response ? json_encode($editingOrder->response, JSON_PRETTY_PRINT) : 'No response data' }}</textarea>
                        </div>
                    </div>

                    <!-- Right Column - Editable Fields -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Editable Fields</h4>

                        <!-- User & Order ID -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                                <a href="{{ route('admin.users.view', $editingOrder?->user_id) }}"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-primary-600 hover:text-primary-700 hover:underline">
                                    {{ $editingOrder?->user?->name ?? 'N/A' }}
                                </a>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order
                                    ID</label>
                                <input type="text" value="{{ $editingOrder?->id }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
                                    disabled>
                            </div>
                        </div>

                        <!-- Link -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link</label>
                            <input type="text" wire:model="editData.link"
                                placeholder="www.example.com/your_profile_identity"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            @error('editData.link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remains & Start Counter -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Remains</label>
                                <input type="number" wire:model="editData.remains" placeholder="Remaining quantity"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('editData.remains')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current
                                    Start Counter</label>
                                <input type="number" wire:model="editData.start_counter" placeholder="Start counter"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('editData.start_counter')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select wire:model="editData.status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="inprogress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="partial">Partial</option>
                                <option value="canceled">Canceled</option>
                                <option value="refunded">Refunded</option>
                                <option value="error">Error</option>
                                <option value="fail">Failed</option>
                            </select>
                            @error('editData.status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                            <textarea wire:model="editData.note" rows="4" placeholder="Add any notes about this order..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                            @error('editData.note')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Progress (Read-only) -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Progress</h5>
                            @php
                                $completed = $editingOrder->quantity - $editingOrder->remains;
                                $progress =
                                    $editingOrder->quantity > 0 ? ($completed / $editingOrder->quantity) * 100 : 0;
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ min($progress, 100) }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400">
                                <span>{{ number_format($completed) }} /
                                    {{ number_format($editingOrder->quantity ?? 0) }}</span>
                                <span>{{ number_format($progress, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-slot name="footer">
                <x-button wire:click="updateOrder" wire:loading.attr="disabled">Update</x-button>
            </x-slot>
        </x-card>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-modal name="delete-order-modal" title="Confirm Deletion" persistent="true">
        <p class="text-gray-600 dark:text-gray-300">Are you sure you want to delete order #{{ $deletingOrder?->id }}?
        </p>
        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeDeleteModal">Cancel</x-button>
            <x-button variant="danger" wire:click="deleteOrder" wire:loading.attr="disabled">Delete</x-button>
        </x-slot>
    </x-modal>

    {{-- order response modal --}}
    <x-modal name="order-response-modal" title="Order API Response">
        <textarea rows="4"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400"
            disabled>{{ $selectedOrderResponse ? json_encode($selectedOrderResponse) : 'No response data' }}</textarea>
    </x-modal>

</div>
