<x-forms.form-field :name="$name" :label="$label" :required="$required" :help="$help">

    <select id="{{ $name }}" name="{{ $name }}" @if ($required) required @endif
        {{ $attributes->merge([
            'class' =>
                'w-full px-3 py-2 dark:bg-gray-700 border border-gray-300 rounded-lg text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-300 dark:focus:border-blue-300 transition-colors ' .
                ($errors->has($name) ? 'border-red-500 focus:ring-red-500 focus:border-red-500 dark:focus:ring-red-400 dark:focus:border-red-400' : ''),
        ]) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach

        {{ $slot ?? '' }}
    </select>

</x-forms.form-field>
