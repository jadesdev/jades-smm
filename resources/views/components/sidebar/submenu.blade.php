@props([
    'icon' => 'fad fa-list',
    'name' => 'Menu Item',
    'submenuItems' => [],
])

<div x-data="{ open: false }">
    <button @click="open = !open"
        class="w-full flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
        <i class="{{ $icon }} mr-3 w-5"></i>
        <span class="flex-1 text-left">{{ $name }}</span>
        <i class="fad fa-chevron-down text-xs transition-transform duration-200"
            :class="{ 'transform rotate-180': open }"></i>
    </button>

    <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1 has-submenu dark:bg-gray-700 bg-primary-700">
        @foreach ($submenuItems as $item)
            <a href="{{ $item['href'] ?? '#' }}" @if ($item['wireNavigate'] ?? true) wire:navigate @endif
                class="flex items-center px-4 py-2 text-sm rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-600 hover:text-white">
                <i class="{{ $item['icon'] ?? 'fat fa-caret-right' }} text-xs mr-1 w-4"></i>
                {{ $item['name'] }}
            </a>
        @endforeach
    </div>
</div>
