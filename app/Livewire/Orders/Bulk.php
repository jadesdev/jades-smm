<?php

namespace App\Livewire\Orders;

use App\Traits\LivewireToast;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Bulk extends Component
{
    use LivewireToast;

    // meta
    public string $metaTitle = 'Bulk Order';

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public function mount() {}

    public function render()
    {
        return view('livewire.orders.bulk');
    }
}
