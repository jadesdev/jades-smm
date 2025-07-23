<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('admin.layouts.main')]
class Index extends Component
{
    use LivewireToast, WithPagination;

    // meta
    public string $metaTitle = "All Users";

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public function render()
    {
        $users = User::orderBy('id', 'desc')->paginate(100);
        return view('livewire.admin.user.index', compact('users'));
    }
}
