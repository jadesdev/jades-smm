@section('title', $metaTitle)
<div>
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">API Providers</h2>
                <x-button wire:click="add" variant='primary'>Add Provider</x-button>
            </div>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            URL
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Balance
                        </th>
                        <th scope="col"
                            class="relative px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($providers as $provider)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $provider->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $provider->url }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($provider->is_active)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $provider->currency }} {{ number_format($provider->balance, 3) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <div class="inline-flex rounded-md shadow-sm gap-1" role="group">
                                    <x-button wire:click="edit({{ $provider->id }})" class="rounded-r-none"
                                        variant="primary" size="xs">Edit</x-button>
                                    <x-dropdown.menu align="right" width="md" position="bottom">
                                        <x-slot name="trigger">
                                            <x-button variant="primary" outline size="sm"
                                                class="rounded-l-none px-2">
                                                <i class="fa fa-chevron-down"></i>
                                            </x-button>
                                        </x-slot>
                                        <x-dropdown.item wire:click="toggleStatus({{ $provider->id }})">
                                            @if ($provider->is_active)
                                                <i class="fa fa-ban fa-fw mr-2"></i> Disable Provider
                                            @else
                                                <i class="fa fa-check-circle fa-fw mr-2"></i> Enable Provider
                                            @endif
                                        </x-dropdown.item>
                                        <x-dropdown.item wire:click="setCurrency({{ $provider->id }})">
                                            <i class="fa fa-dollar-sign fa-fw mr-2"></i> Set Currency
                                        </x-dropdown.item>
                                        <x-dropdown.item wire:click="updateServices({{ $provider->id }})">
                                            <i class="fa fa-tags fa-fw mr-2"></i> Update Services
                                        </x-dropdown.item>
                                        <x-dropdown.item wire:click="updateProviderBalance({{ $provider->id }})">
                                            <i class="fa fa-wallet fa-fw mr-2"></i> Update Provider Balance
                                        </x-dropdown.item>
                                        <x-dropdown.item href="#">
                                            <i class="fa fa-list mr-2"></i> All Services
                                        </x-dropdown.item>
                                        <x-dropdown.divider />
                                        <x-dropdown.item wire:click="delete({{ $provider->id }})" class="text-red-600"
                                            variant="danger">
                                            <i class="fa fa-trash-alt fa-fw mr-2"></i> Delete
                                        </x-dropdown.item>
                                    </x-dropdown.menu>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                No API providers found. <x-button wire:click="add" variant='primary'>Add
                                    Provider</x-button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $providers->links() }}
        </div>
    </x-card>

    {{-- Simplified Add/Edit Modal --}}
    <x-modal name="provider-modal" :title="$editing ? 'Edit API Provider' : 'Add API Provider'" persistent="true">
        <form wire:submit="save">
            <div class="space-y-4">
                {{-- Provider Name --}}
                <x-forms.input wire:model="name" name="name" id="name" type="text" label="Provider Name"
                    required placeholder="Provider Name" class="mt-1 block w-full" />

                {{-- API URL --}}
                <x-forms.input wire:model="url" name="url" id="url" type="url" label="API URL" required
                    class="mt-1 block w-full" placeholder="https://provider.com/api/v2" />

                {{-- API Key --}}
                <x-forms.input wire:model="api_key" name="api_key" id="api_key" type="password" label="API Key"
                    class="mt-1 block w-full" :placeholder="$editing ? 'Leave blank to keep unchanged' : 'Enter the provider API key'" />
                @if ($editing)
                    {{-- Rate --}}
                    <x-forms.input-group name="rate" label="Rate" type="number" placeholder="Enter provider rate"
                        left="1{{ $editing->currency ?? 'USD' }}" right="{{ get_setting('currency_code') }}" required
                        wire:model="rate" />
                @endif
            </div>
        </form>

        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeModal">Cancel</x-button>
            <x-button variant="primary" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">
                    {{ $editing ? 'Save Changes' : 'Add Provider' }}
                </span>
                <span wire:loading wire:target="save">Saving...</span>
            </x-button>
        </x-slot>
    </x-modal>

    {{-- Delete Confirmation Modal --}}
    <x-modal name="delete-provider-modal" title="Confirm Deletion" persistent="true">
        <p class="text-gray-600 dark:text-gray-300">Are you sure you want to delete "{{ $deleting?->name }}"?</p>
        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeDeleteModal">Cancel</x-button>
            <x-button variant="danger" wire:click="confirmDelete" wire:loading.attr="disabled">Delete</x-button>
        </x-slot>
    </x-modal>
    {{-- Update services --}}
    <x-modal name="update-services-modal" title="Sync {{ $syncProvider?->name }} services?">

        <form wire:submit="syncProviderServices">
            <x-forms.select wire:model="syncRequestType" name="syncRequestType" label="Synchronous Request"
                :options="['current' => 'Current Services', 'new' => 'New Services', 'all' => 'All Services']" required />

            {{-- Percentage Increase --}}
            <x-forms.input wire:model="syncPercentage" value="10" name="syncPercentage"
                label="Percentage Increase" type="number" required />
            {{-- Checkbox Options --}}
            <x-forms.form-field name="syncOptions" label="Sync Options" required>
                <div class="space-y-2 mt-2">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="syncOptions" value="new_price"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sync New Price</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="syncOptions" value="original_price" checked
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sync Original Price</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="syncOptions" value="min_max_dripfeed"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sync Min, Max, DripFeed</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="syncOptions" value="service_name"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sync Service Name</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="syncOptions" value="description"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sync Service Description</span>
                    </label>
                </div>
            </x-forms.form-field>

        </form>
        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeUpdateServicesModal">Cancel</x-button>
            <x-button variant="primary" wire:click="syncProviderServices"
                wire:loading.attr="disabled">Sync</x-button>
        </x-slot>
    </x-modal>

    {{-- Currency Modal --}}
    <x-modal name="currency-modal" title="Set Currency" persistent="true">
        <form wire:submit="saveCurrency">
            {{-- Rate --}}
            <x-forms.input-group name="currencyRate" label="Rate" type="number" step="0.001"
                placeholder="Provider Conversion Rate" left="1{{ $currencyEditing->currency ?? 'USD' }}"
                right="{{ get_setting('currency_code') }}" required wire:model="currencyRate" />
        </form>

        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeCurrencyModal">Cancel</x-button>
            <x-button variant="primary" wire:click="saveCurrency" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="saveCurrency">
                    Save Changes
                </span>
                <span wire:loading wire:target="saveCurrency">Saving...</span>
            </x-button>
        </x-slot>
    </x-modal>
</div>
