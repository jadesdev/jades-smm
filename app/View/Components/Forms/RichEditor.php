<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RichEditor extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public string $id = '',
        public string $height = '350px',
        public string $label = 'Description'
    ) {
        $this->name = $name;
        $this->id = $id ?? 'editor_' . uniqid();
        $this->height = $height;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.rich-editor');
    }
}
