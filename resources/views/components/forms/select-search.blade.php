<x-forms.form-field :name="$name" :label="$label" :required="$required" :help="$help">
    <div wire:ignore x-data="selectSearch({
        selectedValue: '{{ old($name, $value) }}',
        placeholder: '{{ $placeholder }}'
    })" x-init="init()" class="relative">
        <select x-ref="select" id="{{ $name }}" {{ $attributes }}>

            {{ $slot }}
        </select>
    </div>
</x-forms.form-field>

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
        <style>
            .ts-control {
                border-radius: 0.5rem !important;
                border-color: #d1d5db !important;
                padding: 0.5rem 0.75rem !important;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            .is-focused .ts-control {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 0.2rem rgb(59 130 246 / 25%) !important;
            }

            .dark .ts-control {
                background-color: #374151 !important;
                /* dark:bg-gray-700 */
                border-color: #4b5563 !important;
                color: #e5e7eb !important;
            }
            .ts-dropdown, .ts-control, .ts-control input {
                color: #141516 !important;
            }
            .dark .ts-dropdown, .ts-control, .ts-control input {
                color: #d1d5db !important;
            }

            .dark .ts-dropdown {
                background: #374151;
                border-color: #4b5563;
            }

            .dark .ts-dropdown .option {
                color: #d1d5db;
            }

            .dark .ts-dropdown .option:hover,
            .dark .ts-dropdown .active {
                background-color: #4b5563;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

        <script>
            function selectSearch(config) {
                return {
                    selectedValue: config.selectedValue,

                    init() {
                        const tom = new TomSelect(this.$refs.select, {
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

                        this.$watch('selectedValue', (newValue) => {
                            tom.setValue(newValue, true);
                        });
                    }
                }
            }
        </script>
    @endpush
@endonce
