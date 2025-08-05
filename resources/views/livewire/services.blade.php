@section('title', $metaTitle)
<div class="container mx-auto px-4 py-8 pt-0">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">Services</h2>
        <div class="w-full flex flex-col md:flex-row items-center space-y-3 md:space-y-0 md:space-x-3">
            <div class="w-full md:w-auto md:min-w-[200px]">
                <x-forms.select wire:model.live="selectedCategory" name="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach ($this->categoriesForFilter as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-forms.select>
            </div>
            <div class="w-full md:flex-grow">
                <x-forms.input wire:model.live.debounce.300ms="search" name="search" label="" type="search"
                    placeholder="Search for services by name..." class="py-2 px-5 bg-white" />
            </div>
        </div>
    </div>

    <div class="space-y-4 relative min-h-[50vh]">
        <!-- Loading state -->
        <div wire:loading.delay.long wire:target="search,selectedCategory,loadFilteredCategories" class="absolute inset-0 w-full">
            <div class="w-full space-y-4">
                @for ($i = 0; $i < 2; $i++)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 animate-pulse">
                        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mb-4"></div>
                        <div class="space-y-3">
                            @for ($j = 0; $j < 5; $j++)
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-8 h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                    <div class="col-span-2 h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                    <div class="col-span-2 h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <!-- Content -->
        <div wire:loading.remove.delay.long wire:target="search,selectedCategory,loadFilteredCategories">

            @forelse ($filteredCategories as $category)
                @php
                    $servicesArray = [];

                    foreach ($category->services as $service) {
                        $servicesArray[] = [
                            'id' => $service->id,
                            'name' => $service->name,
                            'rate' => format_price($service->price),
                            'min' => formatNumber($service->min),
                            'max' => formatNumber($service->max),
                            'desc' => $service->description,
                        ];
                    }
                @endphp

                <x-service-category :id="$category->id" :title="$category->name" iconBg="bg-purple-100 dark:bg-gray-700"
                    iconText="text-purple-600 dark:text-gray-300">
                    <x-service-table :services="$servicesArray" />
                </x-service-category>
            @empty
                <div class="text-center py-12">
                    <div
                        class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No services found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        @if ($search)
                            Try adjusting your search terms or browse all categories.
                        @else
                            No services available in the selected category.
                        @endif
                    </p>
                    @if ($search || $selectedCategory)
                        <button wire:click="clearFilters"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-500 text-white font-semibold rounded-lg transition-colors">
                            <i class="fas fa-refresh mr-2"></i>
                            Show All Services
                        </button>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>

@include('layouts.meta')
