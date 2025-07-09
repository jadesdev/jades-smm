@if ($isLink())
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $getClasses()]) }}
        @if ($disabled) aria-disabled="true" disabled tabindex="-1" @endif>
        @if ($loading)
            <svg class="{{ $getSpinnerClasses() }} mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $getIconClasses() }} mr-2"></i>
        @endif

        {{ $slot }}

        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $getIconClasses() }} ml-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $getClasses()]) }}
        @if ($disabled || $loading) disabled @endif
        @if ($loading) wire:loading.attr="disabled" @endif>
        @if ($loading)
            <svg class="{{ $getSpinnerClasses() }} mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <i class="{{ $icon }} {{ $getIconClasses() }} mr-2"></i>
        @endif

        <span @if ($loading) wire:loading.remove @endif>
            {{ $slot }}
        </span>

        @if ($loading)
            <span wire:loading>
                {{ $attributes->get('loading-text', 'Loading...') }}
            </span>
        @endif

        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} {{ $getIconClasses() }} ml-2"></i>
        @endif
    </button>
@endif
