<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Traits\LivewireToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;

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
                Rule::unique('users', 'username')->ignore(Auth::id())
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
        $apiKey = 'jds_' . Str::random(32);
        
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
        
        Auth::logout();
        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('login', navigate: true);
    }

    public function render()
    {
        return view('livewire.user.profile');
    }
}
