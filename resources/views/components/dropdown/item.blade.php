@if ($href)
    <a href="{{ $href }}"
        {{ $attributes->merge([
            'class' => 'group flex items-center px-4 py-2 text-sm dark:text-gray-300' . $variantClass() . ' ' . $activeClass() . ($disabled ? ' pointer-events-none' : ''),
            'role' => 'menuitem',
            'aria-disabled' => $disabled ? 'true' : null,
        ]) }}>
        {{ $slot }}
    </a>
@else
    <button type="button"
        {{ $attributes->merge([
            'class' => 'group flex items-center w-full px-4 py-2 text-sm text-left dark:text-gray-300' . $variantClass() . ' ' . $activeClass() . ($disabled ? ' pointer-events-none' : ''),
            'role' => 'menuitem',
            'disabled' => $disabled ? 'disabled' : null,
            'aria-disabled' => $disabled ? 'true' : null,
        ]) }}>
        {{ $slot }}
    </button>
@endif
