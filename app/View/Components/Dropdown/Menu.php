<?php

namespace App\View\Components\Dropdown;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Menu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $align = 'left', // left, right, center
        public string $position = 'bottom', // bottom, top
        public string $width = 'auto', // auto, sm, md, lg, xl, full
        public bool $persistent = false, // stays open when clicking inside
        public string $trigger = 'click', // click, hover
        public int $delay = 150, // hover delay in ms
        public bool $arrow = false, // show arrow pointer
        public string $offsetX = '0', // horizontal offset
        public string $offsetY = '4', // vertical offset (gap from trigger)
        public string $zIndex = '50',
        public bool $portal = false, // render in portal (useful for overflow containers)
    ) {}

    public function alignmentClass(): string
    {
        return [
            'left' => 'origin-top-left left-0',
            'right' => 'origin-top-right right-0',
            'center' => 'origin-top left-1/2 transform -translate-x-1/2',
        ][$this->align] ?? 'origin-top-left left-0';
    }

    public function positionClass(): string
    {
        if (! is_numeric($this->offsetY)) {
            $this->offsetY = '4'; // fallback to default
        }

        $baseClass = $this->position === 'top' ? 'bottom-full mb-' : 'top-full mt-';

        return $baseClass.$this->offsetY;
    }

    public function widthClass(): string
    {
        return [
            'auto' => 'w-auto min-w-max',
            'sm' => 'w-48',
            'md' => 'w-56',
            'lg' => 'w-64',
            'xl' => 'w-72',
            'full' => 'w-full',
        ][$this->width] ?? 'w-auto min-w-max';
    }

    public function arrowClass(): string
    {
        if (! $this->arrow) {
            return '';
        }

        $position = $this->position === 'top' ? 'top-full' : 'bottom-full';
        $arrowPosition = $this->position === 'top' ? 'border-t-white border-b-transparent' : 'border-b-white border-t-transparent';

        $alignment = match ($this->align) {
            'right' => 'right-4',
            'center' => 'left-1/2 transform -translate-x-1/2',
            default => 'left-4'
        };

        return "after:content-[''] after:absolute after:{$position} after:{$alignment} after:border-4 after:border-l-transparent after:border-r-transparent after:{$arrowPosition}";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dropdown.menu');
    }
}
