<div {{ $attributes->class(['bg-white dark:bg-gray-900 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 my-2 mb-4']) }}>
    @if (isset($header))
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
            {{ $header }}
        </div>
    @endif
    {{-- The main content area --}}
    <div class="p-4">
        {{ $slot }}
    </div>

    {{-- An optional, styled footer --}}
    @if (isset($footer))
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
            {{ $footer }}
        </div>
    @endif

</div>
