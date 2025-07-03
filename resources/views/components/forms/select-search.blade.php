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
