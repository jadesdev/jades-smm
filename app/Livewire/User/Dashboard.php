<?php

namespace App\Livewire\User;

use App\Traits\LivewireToast;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Dashboard extends Component
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
        $this->metaTitle = 'Dashboard';
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}
