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
            <form class="space-y-8">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 pt-2 mb-4">
                    <h4 class="font-semibold text-lg text-blue-800 mb-2 border-b border-blue-200">@lang('Bulk Order Instructions')</h4>
                    <ul class="text-sm text-blue-700 space-y-1 mb-3">
                        <li>• @lang('Enter each order on a new line')</li>
                        <li>• @lang('Use format: service_id | url | quantity')</li>
                        <li>• @lang('Separate each field with a pipe symbol (|)')</li>
                        <li>• @lang('Maximum 1000 orders per submission')</li>
                    </ul>
                    <div class="bg-blue-100 rounded-lg p-3">
                        <p class="text-xs font-medium text-blue-800 mb-1">@lang('Example:')</p>
                        <code class="text-xs text-blue-700">
                            1 | https://www.instagram.com/p/abc123 | 1000<br>
                            2 | https://www.tiktok.com/@username | 500<br>
                            3 | https://www.youtube.com/watch?v=xyz789 | 2000 <br>
                            4 | https://facebook.com/username | 100
                        </code>
                    </div>
                </div>

                <x-forms.textarea name="bulk_order" id="bulk_order" label="Order Details" :required="true"
                    class="resize-none" :rows="7" placeholder="e.g. 1 | www.instagram.com | 100" />

                <!-- Charge and Balance -->
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 flex justify-between items-center">
                    <div class="text-sm">
                        <span class="block text-slate-600 ">Balance:</span>
                        <span class="font-semibold text-md text-slate-800 ">$150.75</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-sm text-slate-600 ">Charge</span>
                        <span id="charge-amount" class="block text-2xl font-bold text-primary-600 ">$0.00</span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-4 px-8 rounded-xl font-semibold text-lg hover:from-primary-600 hover:to-primary-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                    <div class="flex items-center justify-center space-x-2">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        <span>Place Order</span>
                    </div>
                </button>
            </form>
        </div>
    </div>
    <div class="space-y-6 mb-6 md:col-span-2">

    </div>
</div>



@include('layouts.meta')
