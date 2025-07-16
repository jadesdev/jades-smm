<x-forms.form-field :name="$name" :label="$label" :required="$required" :help="$help">
    <div class="flex overflow-hidden">
        @if ($left)
            <span
                class="inline-flex items-center rounded-r-none px-3 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-r-0 border-gray-300 dark:border-gray-600 text-sm">
                {{ $left }}
            </span>
        @endif

        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
            placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}"
            @if ($required) required @endif
            {{ $attributes->merge([
                'class' =>
                    'w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-300 dark:focus:ring-blue-300 transition-colors ' .
                    ($left ? 'rounded-l-none' : 'rounded-l-lg') .
                    ($right ? ' rounded-r-none' : ' rounded-r-lg') .
                    ($errors->has($name)
                        ? ' border-red-500 focus:ring-red-500 focus:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-400'
                        : ''),
            ]) }} />

        @if ($right)
            <span
                class="inline-flex items-center rounded-l-none px-3 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-l-0 border-gray-300 dark:border-gray-600 text-sm">
                {{ $right }}
            </span>
        @endif
    </div>
</x-forms.form-field>
