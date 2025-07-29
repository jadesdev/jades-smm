<?php

namespace App\Livewire\Admin\User;

use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\LivewireToast;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.main')]
class View extends Component
{
    use LivewireToast;

    public User $user;

    public $userId;

    // Edit form properties
    public string $name = '';

    public string $email = '';

    public string $username = '';

    public string $phone = '';

    public string $country = '';

    public string $address = '';

    public float $balance = 0;

    public float $bonus = 0;

    public bool $is_active = true;

    public bool $email_verify = false;

    public bool $sms_verify = false;

    public string $password = '';

    public string $password_confirmation = '';

    // Stats
    public array $stats = [];

    // UI state
    public bool $showEditForm = false;

    // Meta
    public string $metaTitle;

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'balance' => 'required|numeric|min:0',
            'bonus' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'email_verify' => 'boolean',
            'sms_verify' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    public function mount($id)
    {
        $this->userId = $id;
        $this->user = User::findOrFail($id);

        // Set meta
        $this->metaTitle = "{$this->user->username} profile";
        $this->metaDescription = "View and manage user {$this->user->name}";

        // Initialize form data
        $this->initializeFormData();

        // Load stats
        $this->loadStats();
    }

    private function initializeFormData()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->username = $this->user->username;
        $this->phone = $this->user->phone ?? '';
        $this->country = $this->user->country ?? '';
        $this->address = $this->user->address ?? '';
        $this->balance = $this->user->balance;
        $this->bonus = $this->user->bonus;
        $this->is_active = $this->user->is_active;
        $this->email_verify = $this->user->email_verify;
        $this->sms_verify = $this->user->sms_verify;
    }

    private function loadStats()
    {
        $this->stats = [
            // 'total_orders' => Order::where('user_id', $this->user->id)->count(),
            // 'completed_orders' => Order::where('user_id', $this->user->id)->where('status', 'completed')->count(),
            // 'pending_orders' => Order::where('user_id', $this->user->id)->where('status', 'pending')->count(),
            // 'total_spent' => Order::where('user_id', $this->user->id)->sum('charge'),
            'total_orders' => 12,
            'completed_orders' => 42,
            'pending_orders' => 62,
            'total_spent' => 125,
            'total_deposits' => Transaction::where('user_id', $this->user->id)->where('type', 'deposit')->sum('amount'),
            'total_transactions' => Transaction::where('user_id', $this->user->id)->count(),
            'open_tickets' => SupportTicket::where('user_id', $this->user->id)->where('status', 'open')->count(),
            'total_tickets' => SupportTicket::where('user_id', $this->user->id)->count(),
            'referrals_count' => User::where('ref_id', $this->user->id)->count(),
            'last_login' => $this->user->updated_at,
        ];
    }

    public function toggleEditForm()
    {
        $this->showEditForm = ! $this->showEditForm;
        if (! $this->showEditForm) {
            $this->initializeFormData();
            $this->password = '';
            $this->password_confirmation = '';
        }
    }

    public function updateUser()
    {
        $this->validate();

        try {
            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'phone' => $this->phone,
                'country' => $this->country,
                'address' => $this->address,
                'bonus' => $this->bonus,
                'is_active' => $this->is_active,
                'email_verify' => $this->email_verify,
                'sms_verify' => $this->sms_verify,
            ];

            // Only update password if provided
            if (! empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            $this->user->update($updateData);
            $this->user->refresh();

            // Reload stats in case balance changed
            $this->loadStats();

            $this->showEditForm = false;
            $this->password = '';
            $this->password_confirmation = '';

            $this->successAlert('User updated successfully!');

        } catch (\Exception $e) {
            $this->errorAlert('Failed to update user. Please try again.');
            \Log::error('User update failed: '.$e->getMessage());
        }
    }

    public function toggleUserStatus()
    {
        $this->user->update(['is_active' => ! $this->user->is_active]);
        $this->user->refresh();
        $this->is_active = $this->user->is_active;

        $status = $this->user->is_active ? 'activated' : 'deactivated';
        $this->successAlert("User {$status} successfully!");
    }

    public function verifyEmail()
    {
        $this->user->update([
            'email_verify' => true,
            'email_verified_at' => now(),
        ]);
        $this->user->refresh();
        $this->email_verify = true;

        $this->successAlert('Email verified successfully!');
    }

    public function verifySms()
    {
        $this->user->update(['sms_verify' => true]);
        $this->user->refresh();
        $this->sms_verify = true;

        $this->successAlert('SMS verified successfully!');
    }

    public function adjustBalance($amount, $type = 'add')
    {
        if ($type === 'add') {
            $this->user->increment('balance', $amount);
            $message = 'Added '.format_price($amount).' to user balance';
        } else {
            $this->user->decrement('balance', $amount);
            $message = 'Deducted '.format_price($amount).' from user balance';
        }

        // Create transaction record
        Transaction::create([
            'user_id' => $this->user->id,
            'type' => $type === 'add' ? 'credit' : 'debit',
            'code' => getTrx(),
            'service' => 'deposit',
            'message' => $message,
            'gateway' => $type === 'add' ? 'deposit' : 'system',
            'amount' => $amount,
            'image' => 'deposit.png',
            'charge' => 0,
            'old_balance' => $this->user->balance,
            'new_balance' => $type === 'add' ? $this->user->balance + $amount : $this->user->balance - $amount,
            'meta' => [
                'gateway' => $type === 'add' ? 'deposit' : 'system',
                'amount' => $amount,
                'fee' => 0,
            ],
            'status' => 'successful',
        ]);

        $this->user->refresh();
        $this->balance = $this->user->balance;
        $this->loadStats();

        $this->successAlert($message);
    }

    public function render()
    {
        return view('livewire.admin.user.view');
    }
}
