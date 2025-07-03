@section('title', $metaTitle)
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 pb-10">
    <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden md:col-span-3">
        <div class="p-4 pb-0">
            <div class="flex space-x-4">
                <x-button variant="primary" type="button" size="sm">
                    <i class="fa fa-plus mr-2"></i> New Order
                </x-button>
                <x-button wire:navigate href="{{ route('user.orders.bulk') }}" variant="primary" outline type="button" size="sm">
                    <i class="fa fa-list mr-2"></i> Bulk Order
                </x-button>
            </div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-3 border-b border-slate-200 pb-3">
                Place Order</h1>
        </div>
        <div class="p-6 pt-2">
            <form class="space-y-8">
                {{-- category --}}
                <x-forms.select-search name="category" label="Category" class="">
                    <option value="">Choose a category</option>
                    <option value="instagram">📸 Instagram Services</option>
                    <option value="tiktok">🎵 TikTok Services</option>
                    <option value="youtube">🎬 YouTube Services</option>
                    <option value="twitter1">🐦 Twitter Services</option>
                    <option value="facebook">📘 Facebook Services</option>
                    <option value="instagram">📸 Instagram Services</option>
                    <option value="tiktok2">🎵 TikTok Services</option>
                    <option value="youtube2">🎬 YouTube Services</option>
                    <option value="twitter2">🐦 Twitter Services</option>
                    <option value="facebook2">📘 Facebook Services</option>
                    <option value="instagram2">📸 Instagram Services</option>
                    <option value="tiktok3">🎵 TikTok Services</option>
                    <option value="youtube3">🎬 YouTube Services</option>
                    <option value="twitter3">🐦 Twitter Services</option>
                    <option value="facebook3">📘 Facebook Services</option>
                    <option value="instagram3">📸 Instagram Services</option>
                    <option value="tiktok4">🎵 TikTok Services</option>
                    <option value="youtube4">🎬 YouTube Services</option>
                    <option value="twitter4">🐦 Twitter Services</option>
                    <option value="facebook4">📘 Facebook Services</option>
                </x-forms.select-search>

                {{-- Service Selection --}}
                <x-forms.select-search name="service" label="Service" class="">
                    <option value="">Select a service</option>
                    <option value="ig-reel-views" data-price="0.0024" data-min="100" data-max="2147483647">
                        Instagram Reel Views - Fast Delivery - $0.0024 per 1000</option>
                    <option value="ig-followers" data-price="3.51" data-min="10" data-max="200000">
                        Instagram Followers - USA HQ - $3.51 per 1000</option>
                    <option value="ig-likes" data-price="0.89" data-min="50" data-max="50000">Instagram Likes -
                        Real Users - $0.89 per 1000</option>
                    <option value="ig-reel-views" data-price="0.0024" data-min="100" data-max="2147483647">
                        Instagram Reel Views - Fast Delivery - $0.0024 per 1000</option>
                    <option value="ig-followers" data-price="3.51" data-min="10" data-max="200000">
                        Instagram Followers - USA HQ - $3.51 per 1000</option>
                    <option value="ig-likes" data-price="0.89" data-min="50" data-max="50000">Instagram Likes -
                        Real Users - $0.89 per 1000</option>
                    <option value="ig-reel-views" data-price="0.0024" data-min="100" data-max="2147483647">
                        Instagram Reel Views - Fast Delivery - $0.0024 per 1000</option>
                    <option value="ig-followers" data-price="3.51" data-min="10" data-max="200000">
                        Instagram Followers - USA HQ - $3.51 per 1000</option>
                    <option value="ig-likes" data-price="0.89" data-min="50" data-max="50000">Instagram Likes -
                        Real Users - $0.89 per 1000</option>
                    <option value="ig-reel-views" data-price="0.0024" data-min="100" data-max="2147483647">
                        Instagram Reel Views - Fast Delivery - $0.0024 per 1000</option>
                    <option value="ig-followers" data-price="3.51" data-min="10" data-max="200000">
                        Instagram Followers - USA HQ - $3.51 per 1000</option>
                    <option value="ig-likes" data-price="0.89" data-min="50" data-max="50000">Instagram Likes -
                        Real Users - $0.89 per 1000</option>
                </x-forms.select-search>

                <div id="orderFormField">
                    <x-forms.input name="link" id="linkInput" label="Link" placeholder="Enter link"
                        :required="true" type="text" class="!p-4" />
                </div>

                <!-- Quantity Input -->
                <x-forms.input name="quantity" id="quantityInput" label="Quantity" placeholder="Enter quantity"
                    :required="true" type="number" class="!p-4" min="100" max="2147483647"
                    help="Min: 100 - Max: 2,147,483,647" />

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
        <!-- Service Details Panel -->
        <div id="serviceDetails"
            class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-100 dark:to-indigo-100 rounded-2xl p-6 border border-purple-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Service Details</h3>
                <span class="service-tag">Premium</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fa fa-play" class="w-4 h-4 text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Starts</p>
                        <p class="text-lg font-bold text-green-600">Instant</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fa fa-gauge" class="w-4 h-4 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Speed</p>
                        <p class="text-lg font-bold text-blue-600">50k-100k/hrs</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 tooltip" data-tooltip="Automatic refill if count drops">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fa fa-refresh" class="w-4 h-4 text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Refill</p>
                        <p class="text-lg font-bold text-purple-600">30 Days</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 tooltip" data-tooltip="Quality of users/engagement">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fa fa-star" class="w-4 h-4 text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Quality</p>
                        <p class="text-lg font-bold text-yellow-600">High</p>
                    </div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-800 mb-1">Important Guidelines</h4>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li>• Real and high-quality engagement only</li>
                            <li>• Account must be public during processing</li>
                            <li>• No refunds for policy violations</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.meta')
