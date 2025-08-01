@props(['services'])

<div class="responsive-table overflow-x-auto" x-data="{
    selectedService: null,
    showModal: false,
    openServiceModal(service) {
        this.selectedService = service;
        this.showModal = true;
    },
    closeModal() {
        this.showModal = false;
        this.selectedService = null;
    }
}">

    <table class="w-full min-w-full">
        <thead class="bg-gray-100 dark:bg-gray-600">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">ID</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Service</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Rate per 1000</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Min Order</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Max Order</th>
                <th class="px-4 py-3 text-end text-sm font-medium text-gray-700 dark:text-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($services as $service)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300" data-label="ID">
                        {{ $service['id'] }}
                    </td>
                    <td class="px-4 py-4" data-label="Service">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $service['name'] }}
                        </div>
                    </td>
                    <td class="px-4 py-4" data-label="Rate per 1000">
                        <span
                            class="inline-block text-blue-800 dark:text-blue-300 text-sm font-medium px-2 py-1 rounded">
                            {{ $service['rate'] }}
                        </span>
                    </td>
                    <td class="px-4 py-4" data-label="Min Order">
                        <span
                            class="inline-block text-green-800 dark:text-green-300 text-sm font-medium px-2 py-1 rounded">
                            {{ $service['min'] }}
                        </span>
                    </td>
                    <td class="px-4 py-4" data-label="Max Order">
                        <span
                            class="inline-block text-purple-800 dark:text-purple-300 text-sm font-medium px-2 py-1 rounded">
                            {{ $service['max'] }}
                        </span>
                    </td>
                    <td class="px-4 py-4 actions-cell text-end" data-label="Actions">
                        <div class="flex flex-col sm:flex-row gap-2 justify-end">

                            <button
                                class="bg-primary text-white text-xs px-2 py-1 rounded hover:bg-primary-800 dark:hover:bg-primary-700 transition-colors whitespace-nowrap"
                                @click="openServiceModal(@js($service))">
                                View
                            </button>
                            <a href="{{ route('user.orders.create', ['service_id' => $service['id']]) }}"
                                class="inline-flex items-center px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                Buy
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Service details Modal --}}
    <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0" style="display: none;">

        {{-- Overlay --}}
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>

        {{-- Modal Content --}}
        <div x-show="showModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-2xl max-h-full overflow-hidden flex flex-col"
            @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="selectedService.name"></h3>
                <button @click="closeModal()"
                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-4 flex-1 overflow-y-auto">
                <template x-if="selectedService">
                    <div class="space-y-6">
                        {{-- Service Description --}}
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Description</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed"
                                    x-html="selectedService.desc ? selectedService.desc.split('\n').map(line => line).join('<br>') : selectedService.name">
                                </p>
                            </div>
                        </div>
                        {{-- Service Details Grid (Intentionally hidden for future use or pending requirements) --}}
                        <div class="grid-cols-1 md:grid-cols-2 gap-4 hidden">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Order Limits</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Minimum:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100"
                                            x-text="selectedService.min"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Maximum:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100"
                                            x-text="selectedService.max"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Pricing</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Rate per 1000:</span>
                                        <span class="font-medium text-green-600 dark:text-green-400"
                                            x-text="selectedService.rate"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Min Cost:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100"
                                            x-text="'$' + ((parseFloat(selectedService.rate.replace('$', '')) * parseInt(selectedService.min.replace(/,/g, '')) / 1000).toFixed(2))">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </template>
            </div>

            <div
                class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end space-x-3">
                <a :href="`{{ route('user.orders.bulk') }}?service_id=${selectedService.id}`" wire:navigate
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                    <i class="fas fa-layer-group mr-1"></i> Bulk order
                </a><a :href="`{{ route('user.orders.create') }}?service_id=${selectedService.id}`"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    <i class="fas fa-shopping-cart mr-1"></i> Order Now
                </a>
            </div>
        </div>
    </div>
</div>
