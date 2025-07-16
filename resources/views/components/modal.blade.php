
@php
    $enterClasses = $animationEnterClasses();
    $leaveClasses = $animationLeaveClasses();
@endphp

<div x-data="{
    show: @js($show),
    focusables() {
        return [...$el.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex=\'-1\'])')]
            .filter(el => !el.hasAttribute('disabled'))
    },
    trapFocus(event) {
        let focusableElements = this.focusables();
        let firstFocusable = focusableElements[0];
        let lastFocusable = focusableElements[focusableElements.length - 1];

        if (event.shiftKey && document.activeElement === firstFocusable) {
            lastFocusable.focus();
            event.preventDefault();
        } else if (!event.shiftKey && document.activeElement === lastFocusable) {
            firstFocusable.focus();
            event.preventDefault();
        }
    }
}" 
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-hidden');
            @if(!$scrollable)
                document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px';
            @endif
            $nextTick(() => focusables()[0]?.focus());
        } else {
            document.body.classList.remove('overflow-hidden');
            @if(!$scrollable)
                document.body.style.paddingRight = '';
            @endif
        }
    })"
    x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail.name === '{{ $name }}') show = false"
    @if(!$persistent)
        x-on:keydown.escape.window="show = false"
    @endif
    x-on:keydown.tab="trapFocus($event)"
    x-show="show" 
    class="{{ $containerClass() }} overflow-y-auto"
    style="display: none;"
    role="dialog"
    aria-modal="true"
    @if($title)
        aria-labelledby="modal-title-{{ $name }}"
    @endif
    aria-describedby="modal-content-{{ $name }}">

    {{-- Overlay --}}
    <div x-show="show" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
        class="{{ $overlayClass() }}" 
        @if(!$persistent)
            x-on:click="show = false"
        @endif
        aria-hidden="true">
    </div>

    {{-- Modal Panel --}}
    <div x-show="show" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="{{ $enterClasses['start'] }}"
        x-transition:enter-end="{{ $enterClasses['end'] }}" 
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="{{ $leaveClasses['start'] }}"
        x-transition:leave-end="{{ $leaveClasses['end'] }}"
        class="relative mb-6 bg-white rounded-lg shadow-xl transform transition-all sm:w-full {{ $maxWidthClass() }} sm:mx-auto @if($scrollable) max-h-full overflow-hidden flex flex-col @endif"
        x-on:click.stop>
        
        @if ($title || $closeable)
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-300 @if($scrollable) flex-shrink-0 @endif">
                @if ($title)
                    <h3 id="modal-title-{{ $name }}" class="text-lg font-medium text-gray-900">{{ $title }}</h3>
                @endif
                
                @if ($closeable)
                    <button x-on:click="show = false" 
                        class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1"
                        aria-label="Close modal">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        @endif

        <div id="modal-content-{{ $name }}" class="px-6 py-4 @if($scrollable) flex-1 overflow-y-auto @endif">
            {{ $slot }}
        </div>

        @if (isset($footer))
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 @if($scrollable) flex-shrink-0 @endif">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>