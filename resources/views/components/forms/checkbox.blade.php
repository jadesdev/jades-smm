<div class="mb-4">
    <div class="flex items-center">
        <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{ $value ?? 1 }}"
            @checked(old($name, $checked)) @if ($required) required @endif
            {{ $attributes->merge([
                'class' => 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors',
            ]) }}>

        @if ($label)
            <label for="{{ $name }}" class="ml-2 block text-sm text-gray-900">
                {{ $label }}
                @if ($required)
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
