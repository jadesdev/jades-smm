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
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-lg border border-gray-200 dark:border-gray-700">
            <button class="w-full px-4 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                onclick="toggleSection('jadesmm')">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-purple-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 dark:text-gray-300 text-sm">🚀</span>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">JadeSMM - Top Performing Services</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform dark:text-gray-300" id="jadesmm-icon"></i>
            </button>
            <div id="jadesmm-content" class="hidden border-t border-gray-100 dark:border-gray-600 p-6">
                <p class="text-gray-600 dark:text-gray-300">Top performing services content would go here...</p>
            </div>
        </div>

        <!-- Instagram Cheapest Services -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-lg border border-gray-200 dark:border-gray-700">
            <button class="w-full px-4 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                onclick="toggleSection('instagram-cheap')">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-pink-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <span class="text-pink-600 dark:text-gray-300 text-sm">📷</span>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">Instagram Cheapest Services</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform dark:text-gray-300" id="instagram-cheap-icon"></i>
            </button>
            <div id="instagram-cheap-content" class="hidden border-t border-gray-100 dark:border-gray-600 p-6">
                <p class="text-gray-600 dark:text-gray-300">Instagram cheapest services content would go here...</p>
            </div>
        </div>

        <!-- Instagram Followers - Flag OFF (Expanded) -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-lg border border-gray-200 dark:border-gray-700">
            <button class="w-full px-4 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                onclick="toggleSection('instagram-followers')">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-pink-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <span class="text-pink-600 dark:text-gray-300 text-sm">👥</span>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">Instagram Followers - Flag OFF</span>
                </div>
                <i class="fas fa-chevron-up text-xs transition-transform dark:text-gray-300" id="instagram-followers-icon"></i>
            </button>

            <!-- Expanded Content with Responsive Table -->
            <div id="instagram-followers-content" class="dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                <div class="responsive-table overflow-x-auto">
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
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300" data-label="ID">⭐ 8960</td>
                                <td class="px-4 py-4" data-label="Service">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Instagram Followers - [ 10k/day ] [
                                        NR ] [ Flag Off Only ] Instant</div>
                                </td>
                                <td class="px-4 py-4" data-label="Rate per 1000">
                                    <span
                                        class="inline-block bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-gray-300 text-sm font-medium px-2 py-1 rounded">$1.20</span>
                                </td>
                                <td class="px-4 py-4" data-label="Min Order">
                                    <span
                                        class="inline-block bg-green-100 dark:bg-green-800 text-green-800 dark:text-gray-300 text-sm font-medium px-2 py-1 rounded">100</span>
                                </td>
                                <td class="px-4 py-4" data-label="Max Order">
                                    <span
                                        class="inline-block bg-purple-100 dark:bg-purple-800 text-primary-800 dark:text-gray-300 text-sm font-medium px-2 py-1 rounded">100,000</span>
                                </td>
                                <td class="px-4 py-4 actions-cell text-end" data-label="Actions">
                                    <div class="flex flex-col sm:flex-row gap-2 justify-end">
                                        <button
                                            class="bg-primary text-white dark:text-gray-300 text-xs px-3 py-1 rounded hover:bg-primary-800 dark:hover:bg-primary-700 transition-colors whitespace-nowrap">
                                            View
                                        </button>
                                        <button
                                            class="bg-green-600 text-white dark:text-gray-300 text-xs px-3 py-1 rounded hover:bg-green-700 dark:hover:bg-green-600 transition-colors whitespace-nowrap">
                                            Buy
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300" data-label="ID">⭐ 7458</td>
                                <td class="px-4 py-4" data-label="Service">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Instagram Followers - Smart Boost
                                        [20k-30k/day] | 30 days Refill | Flag Off</div>
                                </td>
                                <td class="px-4 py-4" data-label="Rate per 1000">
                                    <span
                                        class="inline-block bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-gray-300 text-sm font-medium px-2 py-1 rounded">$1.308</span>
                                </td>
                                <td class="px-4 py-4" data-label="Min Order">
                                    <span
                                        class="inline-block bg-green-100 dark:bg-green-800 text-green-800 dark:text-gray-300 text-sm font-medium px-2 py-1 rounded">10</span>
                                </td>
                                <td class="px-4 py-4" data-label="Max Order">
                                    <span
                                        class="inline-block bg-purple-100 dark:bg-purple-800 text-primary-800 dark:text-gray-300 text-sm font-medium px-2 py-1 rounded">10,000,000</span>
                                </td>
                                <td class="px-4 py-4 actions-cell" data-label="Actions">
                                    <div class="flex flex-col sm:flex-row gap-2 justify-end">
                                        <button
                                            class="bg-primary text-white dark:text-gray-300 text-xs px-3 py-1 rounded hover:bg-primary-800 dark:hover:bg-primary-700 transition-colors whitespace-nowrap">
                                            View
                                        </button>
                                        <button
                                            class="bg-green-600 text-white dark:text-gray-300 text-xs px-3 py-1 rounded hover:bg-green-700 dark:hover:bg-green-600 transition-colors whitespace-nowrap">
                                            Buy
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300" data-label="ID">⭐ 7460</td>
                                <td class="px-4 py-4" data-label="Service">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Instagram Followers - Smart Boost
                                        [20k-30k/day] | 90 days Refill | Flag Off</div>
                                </td>
                                <td class="px-4 py-4" data-label="Rate per 1000">
                                    <span
                                        class="inline-block bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-gray-300 text-sm font-medium px-2 py-1 rounded">$1.39</span>
                                </td>
                                <td class="px-4 py-4" data-label="Min Order">
                                    <span
                                        class="inline-block bg-green-100 dark:bg-green-800 text-green-800 dark:text-gray-300 text-sm font-medium px-2 py-1 rounded">10</span>
                                </td>
                                <td class="px-4 py-4" data-label="Max Order">
                                    <span
                                        class="inline-block bg-purple-100 dark:bg-purple-800 text-primary-800 dark:text-gray-300 text-sm font-medium px-2 py-1 rounded">10,000,000</span>
                                </td>
                                <td class="px-4 py-4 actions-cell" data-label="Actions">
                                    <div class="flex flex-col sm:flex-row gap-2 justify-end">
                                        <button
                                            class="bg-primary text-white dark:text-gray-300 text-xs px-3 py-1 rounded hover:bg-primary-800 dark:hover:bg-primary-700 transition-colors whitespace-nowrap">
                                            View
                                        </button>
                                        <button
                                            class="bg-green-600 text-white dark:text-gray-300 text-xs px-3 py-1 rounded hover:bg-green-700 dark:hover:bg-green-600 transition-colors whitespace-nowrap">
                                            Buy
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- YouTube Services -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-lg border border-gray-200 dark:border-gray-700">
            <button class="w-full px-4 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                onclick="toggleSection('youtube')">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-red-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <span class="text-red-600 dark:text-gray-300 text-sm">📺</span>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">YouTube Services</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform dark:text-gray-300" id="youtube-icon"></i>
            </button>
            <div id="youtube-content" class="hidden border-t border-gray-100 dark:border-gray-600 p-6">
                <p class="text-gray-600 dark:text-gray-300">YouTube services content would go here...</p>
            </div>
        </div>

        <!-- TikTok Services -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-lg border border-gray-200 dark:border-gray-700">
            <button class="w-full px-4 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                onclick="toggleSection('tiktok')">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-black rounded-full flex items-center justify-center">
                        <span class="text-white text-sm">🎵</span>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">TikTok Services</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform dark:text-gray-300" id="tiktok-icon"></i>
            </button>
            <div id="tiktok-content" class="hidden border-t border-gray-100 dark:border-gray-600 p-6">
                <p class="text-gray-600 dark:text-gray-300">TikTok services content would go here...</p>
            </div>
        </div>

    </div>
</div>

@assets
    <style>
        /* Custom responsive table styles */
        @media (max-width: 768px) {
            .responsive-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .mobile-stack {
                display: block !important;
            }

            .mobile-stack td {
                display: block;
                text-align: left;
                border: none;
                padding: 0.25rem 0;
            }

            .mobile-stack td:before {
                content: attr(data-label) ": ";
                font-weight: bold;
                color: #374151;
            }

            .mobile-stack .actions-cell {
                border-bottom: 1px solid #e5e7eb;
                padding-bottom: 1rem;
                margin-bottom: 1rem;
            }
        }
    </style>
    <script>
        function toggleSection(sectionId) {
            const content = document.getElementById(sectionId + '-content');
            const icon = document.getElementById(sectionId + '-icon');

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endassets
@include('layouts.meta')
