<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public function __construct(
        public string $name,
        public ?string $title = null,
        public bool $show = false,
        public string $maxWidth = 'lg',
        public bool $closeable = true,
        public string $position = 'top', // top, center, bottom
        public bool $persistent = false,
        public bool $blur = true,
        public string $overlayColor = 'black/50',
        public bool $scrollable = false,
        public string $animation = 'slide', // scale, slide, fade
        public string $maxHeight = 'auto',
        public int $zIndex = 50,
    ) {}

    public function maxWidthClass(): string
    {
        return [
            'xs' => 'sm:max-w-xs',
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
            'full' => 'sm:max-w-full',
        ][$this->maxWidth] ?? 'sm:max-w-2xl';
    }

    public function maxHeightClass(): string
    {
        return [
            'auto' => '',
            'xs' => 'sm:max-h-xs',
            'sm' => 'sm:max-h-sm',
            'md' => 'sm:max-h-md',
            'lg' => 'sm:max-h-lg',
            'xl' => 'sm:max-h-xl',
            '2xl' => 'sm:max-h-2xl',
            '3xl' => 'sm:max-h-3xl',
            '4xl' => 'sm:max-h-4xl',
            '5xl' => 'sm:max-h-5xl',
            '6xl' => 'sm:max-h-6xl',
            '7xl' => 'sm:max-h-7xl',
            'full' => 'sm:max-h-full',
        ][$this->maxHeight] ?? 'sm:max-h-2xl';
    }

    public function positionClass(): string
    {
        return [
            'top' => 'items-start pt-20',
            'center' => 'items-center',
            'bottom' => 'items-end pb-20',
        ][$this->position] ?? 'items-center';
    }
    public function containerClass(): string
    {
        $zIndexClass = match ($this->zIndex) {
            10 => 'z-10',
            20 => 'z-20',
            30 => 'z-30',
            40 => 'z-40',
            50 => 'z-50',
            default => 'z-50',
        };
        $classes = "fixed inset-0 {$zIndexClass} flex px-4 py-6 sm:px-0 "
            . $this->maxHeightClass()
            . $this->positionClass();

        if ($this->scrollable) {
            $classes .= ' overflow-y-auto';
        }

        return $classes;
    }

    public function overlayClass(): string
    {
        $overlayClass = match ($this->overlayColor) {
            'black/50' => 'bg-black/50',
            'black/75' => 'bg-black/75',
            'gray/50'  => 'bg-gray-500/50',
            default    => 'bg-black/50',
        };
        $classes = "fixed inset-0 {$overlayClass}";

        if ($this->blur) {
            $classes .= ' backdrop-blur-sm';
        }

        return $classes;
    }


    public function animationEnterClasses(): array
    {
        return [
            'scale' => [
                'start' => 'opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95',
                'end' => 'opacity-100 translate-y-0 sm:scale-100',
            ],
            'slide' => [
                'start' => 'opacity-0 translate-x-full',
                'end' => 'opacity-100 translate-x-0',
            ],
            'fade' => [
                'start' => 'opacity-0',
                'end' => 'opacity-100',
            ],
        ][$this->animation] ?? [
            'start' => 'opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95',
            'end' => 'opacity-100 translate-y-0 sm:scale-100',
        ];
    }

    public function animationLeaveClasses(): array
    {
        return [
            'scale' => [
                'start' => 'opacity-100 translate-y-0 sm:scale-100',
                'end' => 'opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95',
            ],
            'slide' => [
                'start' => 'opacity-100 translate-x-0',
                'end' => 'opacity-0 translate-x-full',
            ],
            'fade' => [
                'start' => 'opacity-100',
                'end' => 'opacity-0',
            ],
        ][$this->animation] ?? [
            'start' => 'opacity-100 translate-y-0 sm:scale-100',
            'end' => 'opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95',
        ];
    }

    public function render()
    {
        return view('components.modal');
    }
}
