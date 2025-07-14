<?php

namespace App\View\Components\Dropdown;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Item extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $href = null,
        public bool $disabled = false,
        public bool $active = false,
        public string $variant = 'default', // default, danger, success
    ) {}

    public function variantClass(): string
    {
        if ($this->disabled) {
            return 'text-gray-400 cursor-not-allowed';
        }

        return [
            'default' => 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
            'danger' => 'text-red-700 hover:bg-red-50 hover:text-red-900',
            'success' => 'text-green-700 hover:bg-green-50 hover:text-green-900',
        ][$this->variant] ?? 'text-gray-700 hover:bg-gray-100 hover:text-gray-900';
    }

    public function activeClass(): string
    {
        if (! $this->active) {
            return '';
        }

        return [
            'default' => 'bg-gray-100 text-gray-900',
            'danger' => 'bg-red-50 text-red-900',
            'success' => 'bg-green-50 text-green-900',
        ][$this->variant] ?? 'bg-gray-100 text-gray-900';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dropdown.item');
    }
}
