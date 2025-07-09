<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ButtonGroup extends Component
{
    public function __construct(
        public string $size = 'md',
        public bool $vertical = false,
        public bool $attached = true
    ) {}

    public function render()
    {
        return view('components.button-group');
    }

    public function getClasses(): string
    {
        $baseClasses = 'inline-flex';

        if ($this->attached) {
            $baseClasses .= $this->vertical
                ? ' flex-col -space-y-px'
                : ' -space-x-px';
        } else {
            $baseClasses .= $this->vertical
                ? ' flex-col space-y-2'
                : ' space-x-2';
        }

        return $baseClasses;
    }

    public function getButtonClasses(): string
    {
        if (! $this->attached) {
            return '';
        }

        return $this->vertical
            ? 'first:rounded-t-lg last:rounded-b-lg rounded-none'
            : 'first:rounded-l-lg last:rounded-r-lg rounded-none';
    }
}
