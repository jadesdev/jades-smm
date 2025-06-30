@props(['name', 'label' => null, 'required' => false, 'help' => null])

<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}" class="block text-md font-medium text-gray-700 dark:text-gray-200 mb-2">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($help)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-200">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
