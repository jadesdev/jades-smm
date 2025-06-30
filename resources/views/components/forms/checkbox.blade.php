<div class="mb-4">
    <div class="flex items-center">
        <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{ $value ?? 1 }}"
            @checked(old($name, $checked)) @if ($required) required @endif
            {{ $attributes->merge([
                'class' => 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors dark:bg-gray-700 dark:border-gray-600 dark:text-blue-500 dark:focus:ring-blue-500',
            ]) }}>

        @if ($label)
            <label for="{{ $name }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-200">
                {{ $label }}
                @if ($required)
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
