<x-forms.form-field :name="$name" :label="$label" :required="$required" :help="$help">
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        {{ $attributes->merge([
            'class' =>
                'w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical ' .
                ($errors->has($name) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : ''),
        ]) }}>{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</x-forms.form-field>
