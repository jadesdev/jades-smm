@props([
    'href' => '#',
    'icon' => 'fad fa-home',
    'name' => 'Menu Item',
    'wireNavigate' => true,
])

<a href="{{ $href }}" @if ($wireNavigate) wire:navigate @endif
    class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
    <i class="{{ $icon }} mr-3 w-5"></i>
    {{ $name }}
</a>
