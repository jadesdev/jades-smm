@section('title', $metaTitle)
<div>
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Services</h2>
                <x-button wire:click="add" variant='primary'>Add Service</x-button>
            </div>
        </x-slot>

        {{-- Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <x-forms.input wire:model.live.debounce.300ms="search" type="search" placeholder="Search services..." />
            <x-forms.select wire:model.live="statusFilter" name="statusFilter">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </x-forms.select>
            <x-forms.select wire:model.live="categoryFilter" name="categoryFilter">
                <option value="">All Categories</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </x-forms.select>
            <x-forms.select wire:model.live="providerFilter" name="providerFilter">
                <option value="">All Providers</option>
                <option value="manual">Manual</option>
                @foreach ($apiProviders as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </x-forms.select>
        </div>

        @if (!empty($selectedServices))
            <div class="mb-4 flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                    {{ count($selectedServices) }} selected
                </span>
                <x-button wire:click="enableSelected" variant="success">Enable Selected</x-button>
                <x-button wire:click="disableSelected" variant="warning">Disable Selected</x-button>
                <x-button wire:click="confirmBulkDelete" variant="danger">Delete Selected</x-button>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="p-4">
                            <x-forms.checkbox wire:model.live="selectAll" class="mt-3" />
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortByColumn('id')">ID
                            <i class="fa fa-fw fa-sort"></i>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Provider
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortByColumn('name')">Name <i class="fa fa-fw fa-sort"></i></th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Category
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortByColumn('price')">Price <i class="fa fa-fw fa-sort"></i>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer">
                            API Price
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="relative px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($services as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="p-4">
                                <x-forms.checkbox wire:model.live="selectedServices" value="{{ $service->id }}" />
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $service->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <p>{{ $service->api_service_id }}</p>
                                {{ $service->apiProvider?->name ?? 'Manual' }}
                            </td>
                            <td class="px-6 py-4 whitespace- text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ textTrim($service->name) }}</td>
                            <td class="px-6 py-4 whitespace- text-sm text-gray-500 dark:text-gray-400">
                                {{ $service->category?->name ?? 'No category' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ format_price($service->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <p>{{ format_price($service->original_price) }}</p>
                                {{ $service->apiProvider?->currency ?? '$' }}{{ number_format($service->api_price, 2) }}

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-status-badge :status="$service->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                                <x-button wire:click="edit({{ $service->id }})" variant="primary" size="xs"><i
                                        class="fa fa-edit"></i></x-button>
                                <x-button wire:click="delete({{ $service->id }})" variant="danger" size="xs"><i
                                        class="fa fa-trash"></i></x-button>
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

        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </x-card>

    {{-- Add/Edit Modal --}}
    <x-modal name="service-modal" :title="$editing ? 'Edit Service' : 'Add Service'" persistent="true">
        <form wire:submit="save">
            <div class="space-y-4">
                <x-forms.input wire:model="name" label="Service Name" required />
                <x-forms.select wire:model="category_id" label="Category" placeholder="Select Category" required>
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </x-forms.select>
                <x-forms.select wire:model="api_provider_id" label="API Provider (Optional)"
                    placeholder="Select Provider (for API services)">
                    @foreach ($apiProviders as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </x-forms.select>
                <x-forms.input wire:model="api_service_id" label="API Service ID (Optional)" type="number" />
                <x-forms.input wire:model="price" label="Price per 1000" type="number" step="0.01" required />
                <div class="grid grid-cols-2 gap-4">
                    <x-forms.input wire:model="min" label="Min Amount" type="number" required />
                    <x-forms.input wire:model="max" label="Max Amount" type="number" required />
                </div>
                <x-forms.textarea wire:model="description" name="description" label="Description (Optional)" />
                <div class="grid grid-cols-2 gap-4">
                    <x-forms.toggle wire:model="dripfeed" label="Drip-feed" />
                    <x-forms.toggle wire:model="cancel" label="Cancel Button" />
                    <x-forms.toggle wire:model="refill" label="Refill Button" />
                    <x-forms.toggle wire:model="status" label="Active" />
                </div>
            </div>
        </form>

        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeModal">Cancel</x-button>
            <x-button variant="primary" wire:click="save" wire:loading.attr="disabled">
                {{ $editing ? 'Save Changes' : 'Add Service' }}
            </x-button>
        </x-slot>
    </x-modal>

    {{-- Delete Confirmation Modal --}}
    <x-modal name="delete-service-modal" title="Confirm Deletion" persistent="true">
        @if ($bulkDeleteConfirmation)
            <p class="text-gray-600 dark:text-gray-300">Are you sure you want to delete the
                <strong>{{ count($selectedServices) }}</strong> selected services? This action cannot be undone.
            </p>
        @else
            <p class="text-gray-600 dark:text-gray-300">Are you sure you want to delete this service? This action
                cannot be undone.</p>
        @endif

        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeDeleteModal">Cancel</x-button>
            <x-button variant="danger" wire:click="confirmDelete" wire:loading.attr="disabled">Delete</x-button>
        </x-slot>
    </x-modal>
</div>

@include('layouts.meta')
