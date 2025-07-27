@props([
    'id',
    'title',
    'icon' => '',
    'iconBg' => 'bg-purple-100 dark:bg-gray-700',
    'iconText' => 'text-purple-600 dark:text-gray-300',
    'expanded' => false,
    'withTable' => false,
])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-lg border border-gray-200 dark:border-gray-700 mb-4"
    x-data="{ expanded: {{ $expanded ? 'true' : 'false' }} }">

    <button
        class="w-full px-4 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        @click="expanded = !expanded">
        <div class="flex items-center gap-3">
            @if ($icon)
                <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $iconBg }}">
                    <span class="{{ $iconText }} text-sm">{{ $icon }}</span>
                </div>
            @endif
            <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</span>
        </div>
        <i class="fas text-xs transition-transform dark:text-gray-300 duration-200"
            :class="expanded ? 'fa-chevron-up rotate-180' : 'fa-chevron-down'"></i>
    </button>

    <div class="border-t border-gray-100 dark:border-gray-600 p-6" x-show="expanded"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2">
        {{ $slot }}
    </div>
</div>
