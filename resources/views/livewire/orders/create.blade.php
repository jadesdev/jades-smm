@section('title', $metaTitle)
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 pb-10">
    <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden md:col-span-3">
        <div class="p-4 pb-0">
            <div class="flex space-x-4">
                <x-button variant="primary" type="button" size="sm">
                    <i class="fa fa-plus mr-2"></i> New Order
                </x-button>
                <x-button wire:navigate href="{{ route('user.orders.bulk') }}" variant="primary" outline type="button"
                    size="sm">
                    <i class="fa fa-list mr-2"></i> Bulk Order
                </x-button>
            </div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-3 border-b border-slate-200 pb-3">
                Place Order</h1>
        </div>
        <div class="p-6 pt-2">
            <form wire:submit="placeOrder" class="space-y-8">
                <div>
                    <x-forms.select wire:model.live="category_id" name="category_id" label="Category"
                        placeholder="Select a category">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>

                <div>
                    <div wire:loading wire:target="updatedCategoryId" class="text-sm text-gray-500">
                        <i class="fa fa-spinner fa-spin"></i> Loading services...
                    </div>
                    <div wire:loading.remove wire:target="updatedCategoryId">
                        <x-forms.select wire:model.live="service_id" name="service_id" label="Service"
                            placeholder="Select a service" :disabled="!$category_id">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->id }} {{ $service->name }} - {{ format_price($service->price) }} per
                                    1000
                                </option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>

                @if ($selectedService)

                    {{-- Default Link (shown for most services) --}}
                    @if (!in_array($selectedService->type, ['subscriptions']))
                        <div>
                            <x-forms.input wire:model="link" name="link" label="Link"
                                placeholder="https://example.com/your-link" type="text" />
                        </div>
                    @endif

                    {{-- Default Quantity --}}
                    @if (in_array($selectedService->type, [
                            'default',
                            'mentions_with_hashtags',
                            'mentions_hashtag',
                            'mentions_user_followers',
                            'mentions_media_likers',
                            'comment_likes',
                        ]))
                        <div>
                            <x-forms.input wire:model.live.debounce.500ms="quantity" name="quantity" label="Quantity"
                                type="number" :min="$selectedService->min" :max="$selectedService->max" :help="'Min: ' . $selectedService->min . ' - Max: ' . $selectedService->max" />
                        </div>
                    @endif

                    {{-- Custom Comments --}}
                    @if ($selectedService->type === 'custom_comments')
                        <div>
                            <x-forms.textarea wire:model.live="comments" name="comments" label="Comments (One per line)"
                                placeholder="Enter one comment per line..." rows="8"
                                help="The quantity will be automatically calculated based on the number of lines." />
                        </div>
                    @endif

                    {{-- Mentions Custom List --}}
                    @if ($selectedService->type === 'mentions_custom_list')
                        <div>
                            <x-forms.textarea wire:model.live="usernames_custom" name="usernames_custom"
                                label="Usernames (One per line)" placeholder="Enter one username per line..."
                                rows="8"
                                help="The quantity will be automatically calculated based on the number of lines." />
                        </div>
                    @endif

                    {{-- Mentions with Hashtags --}}
                    @if ($selectedService->type === 'mentions_with_hashtags')
                        <div>
                            <x-forms.input wire:model="hashtags" name="hashtags" label="Hashtags"
                                placeholder="e.g., #good,#love" type="text" help="Comma-separated hashtags." />
                        </div>
                        <div>
                            <x-forms.input wire:model="usernames" name="usernames" label="Usernames to mention from"
                                placeholder="e.g., userA,userB" type="text" help="Comma-separated usernames." />
                        </div>
                    @endif

                    {{-- Mentions Hashtag --}}
                    @if ($selectedService->type === 'mentions_hashtag')
                        <div>
                            <x-forms.input wire:model="hashtag" name="hashtag" label="Target Hashtag"
                                placeholder="#target" type="text" />
                        </div>
                    @endif

                    {{-- Mentions User Followers / Comment Likes --}}
                    @if (in_array($selectedService->type, ['mentions_user_followers', 'comment_likes']))
                        <div>
                            <x-forms.input wire:model="username" name="username" label="Target Username"
                                placeholder="e.g., target.user" type="text" />
                        </div>
                    @endif

                    {{-- Mentions Media Likers --}}
                    @if ($selectedService->type === 'mentions_media_likers')
                        <div>
                            <x-forms.input wire:model="media_url" name="media_url" label="Media URL"
                                placeholder="https://..." type="url" />
                        </div>
                    @endif

                    {{-- Subscriptions --}}
                    @if ($selectedService->type === 'subscriptions')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-200 pt-4">
                            <div><x-forms.input wire:model="sub_username" name="sub_username" label="Username"
                                    type="text" /></div>
                            <div><x-forms.input wire:model="sub_posts" name="sub_posts" label="New Posts" type="number"
                                    help="For how many future posts." /></div>
                            <div><x-forms.input wire:model.live="sub_min" name="sub_min" label="Min Quantity"
                                    type="number" /></div>
                            <div><x-forms.input wire:model.live="sub_max" name="sub_max" label="Max Quantity"
                                    type="number" /></div>
                            <div>
                                <x-forms.select wire:model="sub_delay" name="sub_delay" label="Delay (Minutes)">
                                    <option value="0">No Delay</option>
                                    <option value="5">5 Minutes</option>
                                    <option value="15">15 Minutes</option>
                                    <option value="30">30 Minutes</option>
                                    <option value="60">60 Minutes</option>
                                </x-forms.select>
                            </div>
                            <div><x-forms.input wire:model="sub_expiry" name="sub_expiry" label="Expiry"
                                    type="date" /></div>
                        </div>
                    @endif

                    {{-- Drip Feed Option --}}
                    @if ($selectedService->drip_feed)
                        <div class="border-t border-slate-200 pt-4 space-y-4">
                            <x-forms.toggle wire:model.live="is_drip_feed" name="is_drip_feed"
                                label="Enable Drip-Feed" help="This will deliver the order gradually over time." />

                            @if ($is_drip_feed)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-4 border-l-2 border-primary-500">
                                    <div><x-forms.input wire:model.live="runs" name="runs" label="Runs"
                                            type="number" help="How many times to run the order." /></div>
                                    <div><x-forms.input wire:model.live="interval" name="interval"
                                            label="Interval (Minutes)" type="number"
                                            help="Delay between each run." /></div>
                                    <div class="md:col-span-2">
                                        <x-forms.input wire:model="total_quantity" name="total_quantity"
                                            label="Total Quantity" type="number" disabled />
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                @endif

                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-200 dark:to-emerald-200 rounded-lg p-4 flex justify-between items-center">
                    <div class="text-sm">
                        <span class="block text-slate-600 ">Balance:</span>
                        <span class="font-semibold text-md text-slate-800 ">{{ format_price($userBalance) }}</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-sm text-slate-600 ">Charge</span>
                        <span id="charge-amount"
                            class="block text-2xl font-bold text-primary-600 ">{{ format_price($charge) }}</span>
                    </div>
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-primary-600 text-white py-4 px-8 rounded-xl font-semibold text-lg hover:bg-primary-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <div wire:loading.remove wire:target="placeOrder"
                        class="flex items-center justify-center space-x-2">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Place Order</span>
                    </div>
                    <div wire:loading wire:target="placeOrder">
                        Placing Order...
                    </div>
                </button>
            </form>
        </div>
    </div>
    <div class="space-y-6 md:col-span-2">
        @if ($selectedService)
            <!-- Service Details Panel -->
            <div id="serviceDetails"
                class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-100 dark:to-indigo-100 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Service Details</h3>
                    <span class="service-tag bg-primary-600 text-white px-2 py-1 rounded-full">Premium</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <!-- Starts -->
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-bolt text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Starts</p>
                            <p class="text-lg font-bold text-green-600">Instant</p>
                        </div>
                    </div>

                    <!-- Min -->
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-down-1-9 text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Min</p>
                            <p class="text-lg font-bold text-blue-600">{{ $selectedService->min }}</p>
                        </div>
                    </div>

                    <!-- Max -->
                    <div class="flex items-center space-x-2 tooltip"
                        data-tooltip="Maximum quantity allowed per order">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-up-9-1 text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Max</p>
                            <p class="text-lg font-bold text-yellow-600">{{ $selectedService->max }}</p>
                        </div>
                    </div>

                    <!-- Refill -->
                    <div class="flex items-center space-x-2 tooltip" data-tooltip="Automatic refill if count drops">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-sync-alt text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Refill</p>
                            <p class="text-lg font-bold text-purple-600">{{ $selectedService->refill ? 'Yes' : 'No' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                    <div class="flex items-start space-x-3">
                        <i class="fa fa-exclamation-triangle w-5 h-5 text-yellow-600 mt-0.5"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800 mb-1">Important Guidelines</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                {!! nl2br($selectedService->description) !!}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div
                class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-100 dark:to-indigo-100 rounded-2xl p-6 border border-purple-200">
                <p class="font-semibold text-gray-800 text-center">Please select a category and service to see the
                    details.
                </p>
            </div>
        @endif
    </div>
</div>

@include('layouts.meta')
