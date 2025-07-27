<x-forms.form-field :name="$name" :label="$label" :required="$required" :help="$help">
    <div wire:ignore x-data="selectSearch({
        selectedValue: '{{ old($name, $value) }}',
        placeholder: '{{ $placeholder }}'
    })" x-init="init()" class="relative">
        <select x-ref="select" id="{{ $name }}" name="{{ $name }}"
            @if ($required) required @endif
            {{ $attributes->merge([
                'class' =>
                    'w-full px-3 py-2 dark:bg-gray-700 border border-gray-300 rounded-lg text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-300 dark:focus:border-blue-300 transition-colors ' .
                    ($errors->has($name)
                        ? 'border-red-500 focus:ring-red-500 focus:border-red-500 dark:focus:ring-red-400 dark:focus:border-red-400'
                        : ''),
            ]) }}>

            {{ $slot }}
        </select>
    </div>
</x-forms.form-field>

@once

    @push('scripts')
        <script>
            function selectSearch(config) {
                return {
                    selectedValue: config.selectedValue,
                    init() {
                        // Check if TomSelect is already initialized
                        if (this.$refs.select.tomselect) {
                            return;
                        }
                        let tom;
                        try {
                            tom = new TomSelect(this.$refs.select, {
                                items: [this.selectedValue],
                                placeholder: config.placeholder,
                                create: false,
                                onChange: (value) => {
                                    this.selectedValue = value;
                                    const selectedOption = tom.getOption(value);
                                    this.$dispatch('option-selected', {
                                        value: value,
                                        text: selectedOption ? selectedOption.innerText : '',
                                        dataset: selectedOption ? selectedOption.dataset : {}
                                    });
                                    this.$dispatch('input', value);
                                }
                            });
                        } catch (error) {
                            console.error('TomSelect initialization failed:', error);
                            return;
                        }

                        this.$watch('selectedValue', (newValue) => {
                            tom.setValue(newValue, true);
                        });
                    }
                }
            }
        </script>
    @endpush
@endonce
