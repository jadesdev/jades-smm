<div {{ $attributes->class(['bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 my-2']) }}>
    @if (isset($header))
        <div class="px-4 py-2 bg-gray-50 border-t border-gray-200">
            {{ $header }}
        </div>
    @endif
    {{-- The main content area --}}
    <div class="p-4">
        {{ $slot }}
    </div>

    {{-- An optional, styled footer --}}
    @if (isset($footer))
        <div class="px-4 py-2 bg-gray-50 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif

</div>
