@section('title', $metaTitle)

<div>
    <form wire:submit="save">
        <x-card>
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $editing ? 'Edit Service' : 'Add Service' }}
                    </h2>
                    <x-button wire:navigate href="{{ route('admin.services') }}" variant="primary">
                        <i class="fa fa-arrow-left mr-2"></i>Back to List
                    </x-button>
                </div>
            </x-slot>

            {{-- Basic Information --}}
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-1">
                        <x-forms.input wire:model.live="name" label="Service Name"
                            placeholder="e.g., Instagram Followers [Fast]" required />
                    </div>

                    <div class="md:col-span-1">
                        <x-forms.select wire:model.live="category_id" label="Category" placeholder="Select a Category"
                            required>
                            @foreach ($this->categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>

                {{-- Order Type Selection --}}
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Order Type <span class="text-red-500">*</span>
                    </label>

                    <div class="relative flex p-1 bg-gray-100 dark:bg-gray-800 rounded-lg max-w-md">
                        <button type="button" wire:click="setOrderType('manual')"
                            class="relative w-1/2 py-2 px-4 text-sm font-medium text-center transition-colors duration-200 ease-in-out rounded-md {{ $orderType === 'manual' ? 'bg-white dark:bg-gray-600 text-gray-800 dark:text-gray-100 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                            <i class="fa fa-hand-paper mr-2"></i>Manual
                        </button>
                        <button type="button" wire:click="setOrderType('api')"
                            class="relative w-1/2 py-2 px-4 text-sm font-medium text-center transition-colors duration-200 ease-in-out rounded-md {{ $orderType === 'api' ? 'bg-white dark:bg-gray-600 text-gray-800 dark:text-gray-100 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                            <i class="fa fa-cogs mr-2"></i>API
                        </button>
                    </div>
                </div>

                {{-- API Provider Selection --}}
                @if ($orderType === 'api')
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="md:col-span-1">
                            <x-forms.select wire:model.live="api_provider_id" label="API Provider"
                                placeholder="Select a Provider" required>
                                @foreach ($this->apiProviders as $provider)
                                    <option value="{{ $provider->id }}">
                                        {{ $provider->name }} (Rate: {{ $provider->rate }})
                                    </option>
                                @endforeach
                            </x-forms.select>
                        </div>

                        <div class="md:col-span-1">
                            @if ($isLoadingServices)
                                <div class="flex items-center justify-center py-8">
                                    <div class="flex items-center space-x-2 text-blue-600">
                                        <i class="fa fa-spinner fa-spin"></i>
                                        <span>Fetching services from provider...</span>
                                    </div>
                                </div>
                            @else
                                <x-forms.select wire:model.live="api_service_id" label="API Service"
                                    placeholder="{{ empty($providerServices) ? 'Select a provider first' : 'Select a service' }}"
                                    :disabled="empty($providerServices)">
                                    @forelse ($providerServices as $service)
                                        <option value="{{ $service['service'] }}">
                                            {{ $service['name'] }} (ID: {{ $service['service'] }} -
                                            ${{ $service['rate'] }})
                                        </option>
                                    @empty
                                        @if ($api_provider_id)
                                            <option disabled>No services found for this provider</option>
                                        @endif
                                    @endforelse
                                </x-forms.select>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Service Configuration --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <x-forms.input wire:model.live="min" label="Minimum Amount" type="number" min="1"
                            required />
                    </div>

                    <div class="md:col-span-1">
                        <x-forms.input wire:model.live="max" label="Maximum Amount" type="number" min="1"
                            required />
                    </div>

                    <div class="md:col-span-1">
                        <x-forms.select wire:model.live="type" label="Service Type" required>
                            <option value="default">Default</option>
                            <option value="subscriptions">Subscriptions</option>
                            <option value="custom_comments">Custom Comments</option>
                            <option value="custom_comments_package">Custom Comments Package</option>
                            <option value="mentions_with_hashtags">Mentions with Hashtags</option>
                            <option value="mentions_custom_list">Mentions Custom List</option>
                            <option value="mentions_hashtag">Mentions Hashtag</option>
                            <option value="mentions_user_followers">Mentions User Followers</option>
                            <option value="mentions_media_likers">Mentions Media Likers</option>
                            <option value="package">Package</option>
                            <option value="comment_likes">Comment Likes</option>
                            <option value="comment_replies">Comment Replies</option>
                        </x-forms.select>
                    </div>
                </div>

                {{-- Pricing Configuration --}}
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                    <div class="md:col-span-1">
                        <x-forms.input wire:model.live="price" label="Selling Price ({{ get_setting('currency') }})"
                            type="number" step="any" min="0" required />
                        <p class="text-xs text-gray-500 mt-1">Price customers will pay</p>
                    </div>

                    <div class="md:col-span-1">
                        <x-forms.input wire:model.live="api_price" label="API Price" type="number" step="any"
                            min="0" required :readonly="$orderType === 'api'" />
                        <p class="text-xs text-gray-500 mt-1">Original provider price</p>
                    </div>

                    <div class="md:col-span-1">
                        <x-forms.input wire:model.live="original_price"
                            label="Converted Price ({{ get_setting('currency') }})" type="number" step="any"
                            min="0" required :readonly="$orderType === 'api'" />
                        <p class="text-xs text-gray-500 mt-1">Price after rate conversion</p>
                    </div>
                </div>

                {{-- Service Features --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                    <div class="border border-gray-100 dark:border-gray-800 rounded-lg p-2">
                        <x-forms.toggle wire:model.live="status" label="Status" />
                    </div>

                    <div class="border border-gray-100 dark:border-gray-800 rounded-lg p-2">
                        <x-forms.toggle wire:model.live="dripfeed" label="Drip-feed" />
                    </div>

                    <div class="border border-gray-100 dark:border-gray-800 rounded-lg p-2">
                        <x-forms.toggle wire:model.live="refill" label="Refill" />
                    </div>

                    <div class="border border-gray-100 dark:border-gray-800 rounded-lg p-2">
                        <x-forms.toggle wire:model.live="cancel" label="Cancel" />
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <x-forms.textarea wire:model.live="description" label="Description (Optional)" rows="4"
                        name="description" placeholder="Enter service details, terms, or special instructions..."
                        maxlength="1000" />
                    <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        @if ($hasChanges)
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                <i class="fa fa-exclamation-triangle mr-1"></i>
                                Unsaved changes
                            </span>
                        @endif
                    </div>

                    <div class="flex space-x-3">
                        <x-button type="button" wire:navigate href="{{ route('admin.services') }}"
                            variant="outline">
                            Cancel
                        </x-button>

                        <x-button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">
                                <i class="fa fa-save mr-2"></i>
                                {{ $editing ? 'Update Service' : 'Create Service' }}
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fa fa-spinner fa-spin mr-2"></i>
                                Saving...
                            </span>
                        </x-button>
                    </div>
                </div>
            </x-slot>
        </x-card>
    </form>
</div>
