<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Traits\LivewireToast;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

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

        $user = User::where('email', $this->email)->first();
        if ($user) {
            $token = Password::createToken($user);

            $resetLink = url(route('password.reset', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ], false));

            sendNotification('PASSWORD_RESET', $user, [
                'name' => $user->name,
                'first_name' => $user->first_name,
                'email' => $user->email,
                'reset_link' => $resetLink,
            ], [
                'link' => $resetLink,
                'link_text' => 'Reset Password',
            ]);
        }

        $this->successAlert('A reset link will be sent if the account exists.');
    }
}
