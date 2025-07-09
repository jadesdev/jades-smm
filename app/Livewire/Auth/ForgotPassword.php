<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Traits\LivewireToast;

#[Layout('components.layouts.auth')]
class ForgotPassword extends Component
{
    use LivewireToast;
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        $this->successAlert('A reset link will be sent if the account exists.');
    }
}
