<?php

namespace App\Livewire\Admin\User;

use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.main')]
class Settings extends Component
{
    use LivewireToast;

    // meta
    public string $metaTitle = "User Settings";

    public function render()
    {
        return view('livewire.admin.user.settings');
    }
}
