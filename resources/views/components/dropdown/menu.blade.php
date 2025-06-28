<div x-data="{
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },
    @if($trigger === 'hover')
    hoverTimer: null,
    mouseEnter() {
        clearTimeout(this.hoverTimer);
        this.open = true;
    },
    mouseLeave() {
        this.hoverTimer = setTimeout(() => {
            this.open = false;
        }, {{ $delay }});
    }
    @endif
}" 
    x-on:keydown.escape.window="close()"
    x-on:click.away="close()"
    class="relative inline-block text-left">
    
    {{-- Trigger --}}
    <div @if($trigger === 'hover')
            x-on:mouseenter="mouseEnter()"
            x-on:mouseleave="mouseLeave()"
         @else
            x-on:click="toggle()"
         @endif>
        {{ $trigger }}
    </div>

    {{-- Dropdown Menu --}}
    @if($portal)
        <div x-show="open" 
             x-teleport="body"
             style="display: none;">
    @endif
    
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute {{ $positionClass() }} {{ $alignmentClass() }} {{ $widthClass() }} z-{{ $zIndex }} {{ $arrowClass() }}"
         style="display: none; @if($offsetX !== '0') margin-left: {{ $offsetX }}px; @endif"
         @if($trigger === 'hover')
            x-on:mouseenter="mouseEnter()"
            x-on:mouseleave="mouseLeave()"
         @endif
         @if($persistent)
            x-on:click.stop
         @else
            x-on:click="close()"
         @endif>
        
        <div class="rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
            <div class="py-1" role="menu" aria-orientation="vertical">
                {{ $slot }}
            </div>
        </div>
    </div>
    
    @if($portal)
        </div>
    @endif
</div>