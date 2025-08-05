@section('title', $metaTitle)
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 pb-10">
    <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden md:col-span-3">
        <div class="p-4 pb-0">
            <div class="flex space-x-4">
                <x-button variant="primary" wire:navigate href="{{ route('user.orders.create') }}" outline type="button"
                    size="sm">
                    <i class="fa fa-plus mr-2"></i> New Order
                </x-button>
                <x-button variant="primary" type="button" size="sm">
                    <i class="fa fa-list mr-2"></i> Bulk Order
                </x-button>
            </div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-3 border-b border-slate-200 pb-3">
                Bulk Order</h1>
        </div>
        <div class="p-6 pt-2">
            <form wire:submit="submit" class="space-y-4">

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 pt-2 mb-4 hidden">
                    <h4 class="font-semibold text-lg text-blue-800 mb-2 border-b border-blue-200">@lang('Bulk Order Instructions')</h4>
                    <ul class="text-sm text-blue-700 space-y-1 mb-3">
                        <li>• @lang('Select the category and service you want to purchase')</li>
                        <li>• @lang('Add multiple orders by clicking the + button')</li>
                        <li>• @lang('Enter the link and quantity for each order')</li>
                        <li>• @lang('Review the total charge before submitting')</li>
                    </ul>
                </div>

                <x-forms.select wire:model.live="category_id" name="category_id" label="Category"
                    placeholder="Select a category">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-forms.select>

                <div>
                    <div wire:loading wire:target="updatedCategoryId" class="text-sm text-gray-500">
                        <i class="fa fa-spinner fa-spin"></i> Loading services...
                    </div>
                    <div wire:loading.remove wire:target="updatedCategoryId">
                        <x-forms.select wire:model.live="service_id" name="service_id" label="Service"
                            placeholder="Select a service" :disabled="!$category_id">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->id }} - {{ $service->name }} - {{ format_price($service->price) }} per
                                    1000
                                </option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>

                @if ($selectedService)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Service Details</h4>
                        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                            <p><strong>Name:</strong> {{ $selectedService->name }}</p>
                            <p><strong>Price:</strong> {{ format_price($selectedService->price) }} per 1000</p>
                            @if ($selectedService->min)
                                <p><strong>Minimum:</strong> {{ number_format($selectedService->min) }}</p>
                            @endif
                            @if ($selectedService->max)
                                <p><strong>Maximum:</strong> {{ number_format($selectedService->max) }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Add Multiple Orders</h5>
                        <x-button variant="primary" wire:click="addRow" type="button" size="sm">
                            <i class="fa fa-plus mr-1"></i> Add Row
                        </x-button>
                    </div>

                    <div class="space-y-4">
                        @foreach ($orders as $index => $order)
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-center mb-3">
                                    <h6 class="font-medium text-gray-800 dark:text-gray-200">Order {{ $index + 1 }}
                                    </h6>
                                    @if (count($orders) > 1)
                                        <x-button variant="danger" wire:click="removeRow({{ $index }})"
                                            type="button" size="sm">
                                            <i class="fa fa-trash"></i>
                                        </x-button>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-forms.input wire:model.live="orders.{{ $index }}.link"
                                        name="orders.{{ $index }}.link" label="Link"
                                        placeholder="Enter your link" />

                                    <x-forms.input wire:model.live="orders.{{ $index }}.quantity"
                                        name="orders.{{ $index }}.quantity" label="Quantity" type="number"
                                        placeholder="Enter quantity" min="{{ $selectedService->min ?? 1 }}"
                                        max="{{ $selectedService->max ?? 1000 }}" />

                                </div>

                                @if (!empty($order['quantity']) 
                                     && is_numeric($order['quantity']) 
                                     && $order['quantity'] > 0 
                                     && $selectedService)  
                                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">  
                                        <strong>Order Cost:</strong>  
                                        {{ e(format_price(($order['quantity'] / 1000) * $selectedService->price)) }}  
                                    </div>  
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @error('orders')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Charge and Balance -->
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900 dark:to-emerald-900 rounded-lg p-4 flex justify-between items-center">
                    <div class="text-sm">
                        <span class="block text-slate-600 dark:text-slate-300">Balance:</span>
                        <span
                            class="font-semibold text-md text-slate-800 dark:text-slate-200">{{ format_price($userBalance) }}</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-sm text-slate-600 dark:text-slate-300">Total Charge</span>
                        <span
                            class="block text-2xl font-bold text-primary-600 dark:text-primary-400">{{ format_price($charge) }}</span>
                    </div>
                </div>

                @if ($charge > $userBalance)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 my-4">
                        <div class="flex items-center">
                            <i class="fa fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-red-700 font-medium">Insufficient balance to place this order.</span>
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <button type="submit" @disabled(!$selectedService || $charge == 0 || $charge > $userBalance)
                    class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-4 px-8 rounded-xl font-semibold text-lg hover:from-primary-600 hover:to-primary-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <div class="flex items-center justify-center space-x-2">
                        <div wire:loading wire:target="submit">
                            <i class="fa fa-spinner fa-spin w-5 h-5"></i>
                        </div>
                        <div wire:loading.remove wire:target="submit">
                            <i class="fa fa-shopping-cart w-5 h-5"></i>
                        </div>
                        <span>Place Bulk Order</span>
                    </div>
                </button>
            </form>
        </div>
    </div>
    <div class="space-y-6 mb-6 md:col-span-2">

    </div>
</div>



@include('layouts.meta')
