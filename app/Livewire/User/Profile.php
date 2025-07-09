<?php

namespace App\Livewire\User;

use App\Traits\LivewireToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Profile extends Component
{
    use LivewireToast;

    // Profile form
    public string $name = '';

    public string $username = '';

    public string $phone = '';

    public string $email = '';

    // Password change form
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    // Delete account
    public string $delete_password = '';

    public bool $confirmingUserDeletion = false;

    // API
    public string $api_token = '';

    // Meta
    public string $metaTitle = 'Account';

    public string $metaDescription = 'Manage your account settings';

    public string $metaKeywords = 'profile, account, settings, password, api';

    public string $metaImage = '';

    public function mount(): void
    {
        $this->loadUserData();
    }

    protected function loadUserData(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->phone = $user->phone ?? '';
        $this->email = $user->email;
        $this->api_token = $user->api_token ?? '';
    }

    public function updateProfile(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore(Auth::id()),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = Auth::user();
        $user->update($validated);

        $this->successAlert('Profile updated successfully!');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->successAlert('Password updated successfully!');
    }

    public function generateApiKey(): void
    {
        $token = Auth::user()->username.Str::random(12);

        $apiKey = bin2hex($token);
        Auth::user()->update([
            'api_token' => $apiKey,
        ]);

        $this->api_token = $apiKey;
        $this->successAlert('New API key generated successfully!');
    }

    public function confirmUserDeletion(): void
    {
        $this->resetErrorBag();
        $this->delete_password = '';
        $this->confirmingUserDeletion = true;
    }

    public function deleteAccount(): void
    {
        $this->validate([
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        try {
            DB::transaction(function () use ($user) {
                // Delete user's support messages
                $user->supportMessages()->delete();

                // Delete user's support tickets (and their related messages)
                $user->supportTickets()->delete();

                // Logout user
                Auth::logout();

                // Delete the user
                $user->delete();

                // Invalidate session
                session()->invalidate();
                session()->regenerateToken();
            });

            $this->successAlert('Account deleted successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Failed to delete account. Please try again.');

            return;
        }

        $this->redirectRoute('login', navigate: true);
    }

    public function render()
    {
        return view('livewire.user.profile');
    }
}
