<?php

namespace App\Livewire\User;

use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('user.layouts.main')]
class Wallet extends Component
{
    use LivewireToast;


    // meta
    public string $metaTitle;

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public function mount()
    {
        // set meta
        $this->metaTitle = "Wallet";
    }

    public function render()
    {
        return view('livewire.user.wallet');
    }
}
