<div class="mb-4">
    @if ($label)
        <fieldset class="dark:text-gray-400">
            <legend class="block text-sm font-medium dark:text-gray-200 mb-2">
                {{ $label }}
                @if ($required)
                    <span class="text-red-500">*</span>
                @endif
            </legend>
    @endif

    <div class="space-y-2">
        @foreach ($options as $optionValue => $optionLabel)
            <div class="flex items-center">
                <input type="radio" id="{{ $name }}_{{ $optionValue }}" name="{{ $name }}"
                    value="{{ $optionValue }}" @checked(old($name, $value) == $optionValue)
                    @if ($required) required @endif
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300
                           dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-blue-500
                           transition-colors">
                <label for="{{ $name }}_{{ $optionValue }}" class="ml-2 block text-sm dark:text-gray-400">
                    {{ $optionLabel }}
                </label>
            </div>
        @endforeach
    </div>

    @if ($label)
        </fieldset>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
