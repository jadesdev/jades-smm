<?php

namespace App\Livewire\User;

use App\Traits\LivewireToast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Referrals extends Component
{
    use LivewireToast;

    // Referral data
    public string $referralLink = '';

    public int $referralCount = 0;

    public int $referralRate = 3;

    public float $minWithdrawal = 10.00;

    public float $referralBalance = 0;

    public Collection $recentReferrals;

    // UI State
    public bool $isLoading = true;

    // Meta
    public string $metaTitle = 'Referral Program';

    public string $metaDescription = 'Invite friends and earn commissions on their purchases';

    public string $metaKeywords = 'referrals, invite friends, earn money, affiliate program';

    public string $metaImage = '';

    public function mount(): void
    {
        $this->loadReferralData();
    }

    protected function loadReferralData(): void
    {
        $user = Auth::user();
        if (! $user || ! $user->username) {
            $this->isLoading = false;

            return;
        }
        $this->referralBalance = $user->bonus;
        $this->referralLink = route('register') . '?ref=' . urlencode($user->username);
        $this->loadReferrals();
        $this->calculateStats();
        $this->isLoading = false;
    }

    protected function loadReferrals(): void
    {
        $this->recentReferrals = Auth::user()->referrals()->get();
    }

    protected function calculateStats(): void
    {
        $this->referralRate = sys_setting('referral_bonus', 2);
        $this->referralCount = count($this->recentReferrals);
    }

    public function render()
    {
        return view('livewire.user.referrals');
    }
}
