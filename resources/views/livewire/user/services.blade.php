@section('title', $metaTitle)
<div class="mx-auto px-4 py-8 pt-0">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">Services</h2>
        <div class="w-full flex flex-col md:flex-row items-center space-y-3 md:space-y-0 md:space-x-3">
            <button
                class="w-full md:w-auto flex-shrink-0 mb-4 flex items-center justify-center space-x-2 bg-primary hover:bg-primary-500 transition-colors text-white font-semibold py-3 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0b1114] focus:ring-primary">
                <i class="far fa-filter"></i>
                <span>Filter Category</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <div class="w-full md:flex-grow flex items-center space-x-3">
                <div class="flex-grow">
                    <x-forms.input name="search" label="" placeholder="Search" class="py-3 px-5 bg-white" />
                </div>
                <x-button variant="primary" class="py-3 px-6 flex-shrink-0 mb-4">
                    <i class="fad fa-search"></i> <span class="hidden md:inline ml-1">Search</span>
                </x-button>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <x-service-category id="jadesmm" title="JadeSMM - Top Performing Services" icon="🚀"
            iconBg="bg-purple-100 dark:bg-gray-700" iconText="text-purple-600 dark:text-gray-300">
            <p class="text-gray-600 dark:text-gray-300">Top performing services content would go here...</p>
        </x-service-category>

        <x-service-category id="instagram-cheap" title="Instagram Cheapest Services" icon="📷"
            iconBg="bg-pink-100 dark:bg-gray-700" iconText="text-pink-600 dark:text-gray-300">
            <p class="text-gray-600 dark:text-gray-300">Instagram cheapest services content would go here...</p>
        </x-service-category>

        <x-service-category id="instagram-followers" title="Instagram Followers - Flag OFF" icon="👥"
            iconBg="bg-pink-100 dark:bg-gray-700" iconText="text-pink-600 dark:text-gray-300">
            <x-service-table :services="[
                [
                    'id' => '8960',
                    'name' => 'Instagram Followers - [ 10k/day ] [ NR ] [ Flag Off Only ] Instant',
                    'rate' => '$1.20',
                    'min' => '100',
                    'max' => '100,000',
                    'desc' => 'Instagram Followers - [ 10k/day ] [ NR ] [ Flag Off Only ] Instant',
                ],
                [
                    'id' => '7458',
                    'name' => 'Instagram Followers - Smart Boost [20k-30k/day] | 30 days Refill | Flag Off',
                    'rate' => '$1.308',
                    'min' => '10',
                    'max' => '10,000,000',
                    'desc' => ('Instagram Followers - Smart Boost [20k-30k/day] | 30 days Refill | Flag Off.
                     This service is great for people who want to boost their Instagram followers quickly and safely. 
                     It is a Smart Boost service which means that it will automatically detect your Instagram account\'s pace and adjust the speed of followers accordingly. 
                     It also has a 30 day refill warranty which means that if you lose any followers within 30 days of your purchase, we will refund them for free. 
                     This service is also flagged off which means that it is not marked as spam by Instagram and is therefore much safer than other services.'),
                ],
                [
                    'id' => '7460',
                    'name' => 'Instagram Followers - Smart Boost [20k-30k/day] | 90 days Refill | Flag Off',
                    'rate' => '$1.39',
                    'min' => '10',
                    'max' => '10,000,000',
                    'desc' => 'Instagram Followers - Smart Boost [20k-30k/day] | 90 days Refill | Flag Off',
                ],
            ]" />
        </x-service-category>

        <x-service-category id="youtube" title="YouTube Services" icon="📺" iconBg="bg-red-100 dark:bg-gray-700"
            iconText="text-red-600 dark:text-gray-300">
            <p class="text-gray-600 dark:text-gray-300">YouTube services content would go here...</p>
        </x-service-category>

        <x-service-category id="tiktok" title="TikTok Services" icon="🎵" iconBg="bg-black dark:bg-gray-700"
            iconText="text-white dark:text-gray-300">
            <p class="text-gray-600 dark:text-gray-300">TikTok services content would go here...</p>
        </x-service-category>

    </div>
</div>

@include('layouts.meta')
