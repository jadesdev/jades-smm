<div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ $label }}
    </label>
    <input type="file" name="{{ $name }}" accept="{{ $accept }}" wire:model="{{ $name }}"
        class="block w-full text-sm text-gray-900 dark:text-gray-100
           file:mr-4 file:py-2 file:px-4
           file:rounded-md file:border-0
           file:font-semibold file:text-sm
           file:bg-primary-600 file:text-white
           hover:file:bg-primary-700
           dark:file:bg-primary-500 dark:hover:file:bg-primary-400
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">

    @if ($value)
        <div class="{{ $previewClass }}">
            <img src="{{ my_asset($value) }}" alt="{{ $label }}" class="h-full object-contain">
        </div>
    @endif
</div>
