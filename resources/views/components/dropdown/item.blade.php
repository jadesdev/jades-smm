@if ($href)
    <a href="{{ $href }}"
        class="group flex items-center px-4 py-2 text-sm {{ $variantClass() }} {{ $activeClass() }} {{ $disabled ? 'pointer-events-none' : '' }}"
        role="menuitem" @if ($disabled) aria-disabled="true" @endif>
        {{ $slot }}
    </a>
@else
    <button type="button"
        class="group flex items-center w-full px-4 py-2 text-sm text-left {{ $variantClass() }} {{ $activeClass() }} {{ $disabled ? 'pointer-events-none' : '' }}"
        role="menuitem" @if ($disabled) disabled aria-disabled="true" @endif>
        {{ $slot }}
    </button>
@endif
