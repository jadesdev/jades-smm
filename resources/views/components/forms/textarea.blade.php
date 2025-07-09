<x-forms.form-field :name="$name" :label="$label" :required="$required" :help="$help">
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        {{ $attributes->merge([
            'class' =>
                'w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-300 dark:focus:border-blue-300 transition-colors resize-vertical bg-white dark:bg-gray-700 ' .
                ($errors->has($name) ? 'border-red-500 focus:ring-red-500 focus:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-400' : ''),
        ]) }}>{{ old($name, $value) }}</textarea>

</x-forms.form-field>
