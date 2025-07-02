<?php

namespace App\Livewire\Support;

use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('user.layouts.main')]
class Ticket extends Component
{
    use LivewireToast;


    // meta
    public string $metaTitle;

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public function mount($id)
    {
        // set meta
        $this->metaTitle = "Ticket Details";
    }

    public function render()
    {
        return view('livewire.support.ticket');
    }
}
