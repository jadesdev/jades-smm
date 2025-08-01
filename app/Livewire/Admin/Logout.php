<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        Auth::guard('admin')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect(route('admin.login'));
    }
}
