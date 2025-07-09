<x-forms.form-field :name="$name" :label="$label" :required="$required" :help="$help">

    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}" @if ($required) required @endif
        {{ $attributes->merge([
            'class' =>
                'w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 color-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-300 dark:focus:ring-blue-300 dark:focus:bg-gray-700 transition-colors ' .
                ($errors->has($name) ? 'border-red-500 focus:ring-red-500 focus:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-400' : ''),
        ]) }}>
</x-forms.form-field>
