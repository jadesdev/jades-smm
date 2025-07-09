<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public string $variant = 'primary',
        public string $size = 'md',
        public string $type = 'button',
        public bool $disabled = false,
        public bool $loading = false,
        public string $href = '',
        public string $icon = '',
        public string $iconPosition = 'left',
        public bool $outline = false,
        public bool $ghost = false
    ) {}

    public function render()
    {
        return view('components.button');
    }

    public function getClasses(): string
    {
        $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

        // Size classes
        $sizeClasses = [
            'xs' => 'px-2.5 py-1.5 text-xs',
            'sm' => 'px-3 py-2 text-sm',
            'md' => 'px-4 py-2.5 text-sm',
            'lg' => 'px-5 py-3 text-base',
            'xl' => 'px-6 py-3.5 text-base',
        ];

        // Variant classes
        if ($this->ghost) {
            $variantClasses = $this->getGhostClasses();
        } elseif ($this->outline) {
            $variantClasses = $this->getOutlineClasses();
        } else {
            $variantClasses = $this->getSolidClasses();
        }

        return implode(' ', [
            $baseClasses,
            $sizeClasses[$this->size] ?? $sizeClasses['md'],
            $variantClasses,
        ]);
    }

    private function getSolidClasses(): string
    {
        $variants = [
            'primary' => 'bg-primary-600 hover:bg-primary-700 text-white focus:ring-primary-500',
            'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
            'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
            'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
            'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-500',
            'info' => 'bg-cyan-600 hover:bg-cyan-700 text-white focus:ring-cyan-500',
            'light' => 'bg-gray-100 hover:bg-gray-200 text-gray-900 focus:ring-gray-500',
            'dark' => 'bg-gray-900 hover:bg-gray-800 text-white focus:ring-gray-500',
        ];

        return $variants[$this->variant] ?? $variants['primary'];
    }

    private function getOutlineClasses(): string
    {
        $variants = [
            'primary' => 'border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white focus:ring-primary-500',
            'secondary' => 'border-2 border-gray-600 text-gray-600 hover:bg-gray-600 hover:text-white focus:ring-gray-500',
            'success' => 'border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white focus:ring-green-500',
            'danger' => 'border-2 border-red-600 text-red-600 hover:bg-red-600 hover:text-white focus:ring-red-500',
            'warning' => 'border-2 border-yellow-500 text-yellow-600 hover:bg-yellow-500 hover:text-white focus:ring-yellow-500',
            'info' => 'border-2 border-cyan-600 text-cyan-600 hover:bg-cyan-600 hover:text-white focus:ring-cyan-500',
            'light' => 'border-2 border-gray-300 text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
            'dark' => 'border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white focus:ring-gray-500',
        ];

        return $variants[$this->variant] ?? $variants['primary'];
    }

    private function getGhostClasses(): string
    {
        $variants = [
            'primary' => 'text-primary-600 hover:bg-primary-50 focus:ring-primary-500',
            'secondary' => 'text-gray-600 hover:bg-gray-50 focus:ring-gray-500',
            'success' => 'text-green-600 hover:bg-green-50 focus:ring-green-500',
            'danger' => 'text-red-600 hover:bg-red-50 focus:ring-red-500',
            'warning' => 'text-yellow-600 hover:bg-yellow-50 focus:ring-yellow-500',
            'info' => 'text-cyan-600 hover:bg-cyan-50 focus:ring-cyan-500',
            'light' => 'text-gray-500 hover:bg-gray-50 focus:ring-gray-500',
            'dark' => 'text-gray-900 hover:bg-gray-100 focus:ring-gray-500',
        ];

        return $variants[$this->variant] ?? $variants['primary'];
    }

    public function isLink(): bool
    {
        return ! empty($this->href);
    }

    public function getIconClasses(): string
    {
        $sizeMap = [
            'xs' => 'w-3 h-3',
            'sm' => 'w-4 h-4',
            'md' => 'w-4 h-4',
            'lg' => 'w-5 h-5',
            'xl' => 'w-5 h-5',
        ];

        return $sizeMap[$this->size] ?? $sizeMap['md'];
    }

    public function getSpinnerClasses(): string
    {
        $sizeMap = [
            'xs' => 'w-3 h-3',
            'sm' => 'w-4 h-4',
            'md' => 'w-4 h-4',
            'lg' => 'w-5 h-5',
            'xl' => 'w-5 h-5',
        ];

        return 'animate-spin '.($sizeMap[$this->size] ?? $sizeMap['md']);
    }
}
