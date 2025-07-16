@props(['label', 'id' => null])

@php
    $id = $id ?? $attributes->whereStartsWith('wire:model')->first() ?? md5(serialize($attributes));
@endphp

<div class="flex items-center justify-between mb-4">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-900 dark:text-gray-200">
        {{ $label }}
    </label>
    @endif

    <label for="{{ $id }}" class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" id="{{ $id }}" {{ $attributes }} class="sr-only peer">
        <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-2 peer-focus:ring-offset-2 peer-focus:ring-blue-500 dark:peer-focus:ring-offset-gray-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
    </label>
</div>
