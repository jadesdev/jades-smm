<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Traits\LivewireToast;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    use LivewireToast;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $terms = false;

    public string $referral_code = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
            'referral_code' => ['nullable', 'string', 'max:255', 'exists:users,username'],
        ], [
            'terms.accepted' => 'You must accept the terms and conditions to register.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $referUser = User::where('username', $this->referral_code)->first();
        if ($referUser && $referUser->email !== $this->email) {
            $validated['ref_id'] = $referUser->id;
        }

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->successAlert('Registration successful! Welcome to our platform.');
        $this->redirect(route('user.dashboard', absolute: false), navigate: true);
    }

    /**
     * Mount the component with referral code from URL if present
     */
    public function mount(): void
    {
        $this->referral_code = request()->query('ref', '');
    }

    /**
     * Validate referral code
     */
    public function updatedReferralCode()
    {
        $this->validateOnly('referral_code', [
            'referral_code' => ['nullable', 'string', 'max:255', 'exists:users,username'],
        ]);
    }
}
