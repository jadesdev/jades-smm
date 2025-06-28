<x-forms.form-field :name="$name" :label="$label" :required="$required" :help="$help">

    <select id="{{ $name }}" name="{{ $name }}" @if ($required) required @endif
        {{ $attributes->merge([
            'class' =>
                'w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ' .
                ($errors->has($name) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : ''),
        ]) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</x-forms.form-field>
