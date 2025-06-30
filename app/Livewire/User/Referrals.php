<?php

namespace App\Livewire\User;

use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('user.layouts.main')]
class Referrals extends Component
{
    use LivewireToast;

    public $referralLink;
    public $referralCount;
    public $referralRate;
    public $minWithdrawal;
    public $recentReferrals = [];
    // meta
    public string $metaTitle;

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public function loadReferrals()
    {
        return [
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'avatar' => null,
                'created_at' => now()->subDays(2),
                'status' => 'active',
                'earned' => 15.5,
            ],
            [
                'username' => 'jane_smith',
                'email' => 'jane@example.com',
                'avatar' => 'https://randomuser.me/api/portraits/women/22.jpg',
                'created_at' => now()->subDays(5),
                'status' => 'active',
                'earned' => 8.25,
            ],
            [
                'username' => 'pending_user',
                'email' => 'pending@example.com',
                'avatar' => null,
                'created_at' => now()->subDays(1),
                'status' => 'pending',
                'earned' => 0.0,
            ],
            [
                'username' => 'inactive_amy',
                'email' => 'amy@example.com',
                'avatar' => 'https://randomuser.me/api/portraits/women/63.jpg',
                'created_at' => now()->subWeeks(2),
                'status' => 'inactive',
                'earned' => 42.75,
            ],
            [
                'username' => 'mike_tyson',
                'email' => 'mike@example.com',
                'avatar' => null,
                'created_at' => now()->subDays(3),
                'status' => 'active',
                'earned' => 27.3,
            ],
        ];
    }
    public function mount()
    {
        // set meta
        $this->metaTitle = "Referrals";
    }


    public function render()
    {
        $this->recentReferrals = $this->loadReferrals();
        return view('livewire.user.referrals');
    }
}
