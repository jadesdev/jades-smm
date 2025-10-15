<?php

namespace App\Livewire\User;

use App\Models\Transaction;
use App\Models\User;
use App\Services\BankAccountService;
use App\Services\DepositService;
use App\Traits\LivewireToast;
use Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('user.layouts.main')]
class Wallet extends Component
{
    use LivewireToast;


    public $amount = '';

    public $selectedGateway = '';

    public $showBalance = true;

    public $balance = 0;

    public $referralBalance = 0;

    // meta
    public string $metaTitle = 'Wallet';

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public bool $processingPayment = false;
    public $hasAccount = false;
    public $kyc_type = 'bvn';
    public $kyc_number = '';
    public $bankAccount = null;

    public $allGateways = [
        'paypal' => [
            'name' => 'PayPal',
            'fee' => '0.00%',
            'icon' => 'fab fa-paypal',
            'image' => 'paypal.png',
        ],
        'flutterwave' => [
            'name' => 'Flutterwave',
            'fee' => '0.00%',
            'icon' => 'fab fa-card',
            'image' => 'card.png',
        ],
        'paystack' => [
            'name' => 'Paystack',
            'fee' => '0.00%',
            'icon' => 'fa fa-card',
            'image' => 'card.png',
        ],
        'korapay' => [
            'name' => 'Korapay',
            'fee' => '0.00%',
            'icon' => 'fa fa-card',
            'image' => 'card.png',
        ],
        // 'crypto' => [
        //     'name' => 'Crypto',
        //     'fee' => '0.00%',
        //     'icon' => 'fab fa-bitcoin',
        //     'image' => 'cryptomus.png',
        // ],
    ];

    public $gateways = [];

    public function toggleBalance()
    {
        $this->showBalance = ! $this->showBalance;
    }

    public function updatedSelectedGateway($value)
    {
        if (! array_key_exists($value, $this->gateways)) {
            $this->selectedGateway = '';
            $this->addError('selectedGateway', 'Invalid payment gateway selected.');
        }
        $this->selectedGateway = $value;
    }

    public function updatedAmount()
    {
        $this->amount = max(0, floatval($this->amount));
    }

    public function deposit()
    {
        // Validate
        $this->validate([
            'amount' => 'required|numeric|min:100|max:100000',
            'selectedGateway' => 'required|string',
        ]);
        $this->processingPayment = true;

        try {
            $user = User::find(Auth::id());
            $depositService = app(DepositService::class);

            return $depositService->initiateDeposit(
                (float) $this->amount,
                $this->selectedGateway,
                $user
            );
            $this->successAlert('Deposit successful!');
        } catch (Exception $exception) {
            Log::error('Deposit failed: ' . $exception->getMessage());
            $this->errorAlert($exception->getMessage() ?: 'Unable to process your payment at this time. Please try again later.');
        }
    }

    public function getDepositButtonTextProperty()
    {
        if ($this->amount) {
            return 'Deposit ' . format_price(floatval($this->amount), 2);
        }

        return 'Deposit ' . format_price(0, 2);
    }

    public function getIsDepositValidProperty()
    {
        return ! empty($this->amount) && ! empty($this->selectedGateway) && floatval($this->amount) > 0;
    }

    public function generateAccount()
    {
        $this->validate([
            'kyc_type' => 'required|string|in:bvn,nin',
            'kyc_number' => 'required|string|numeric',
        ]);
        $user = Auth::user();
        $bankAccountService = app(BankAccountService::class);
        try {
            $user->update([
                'kyc_type' => $this->kyc_type,
                'kyc_number' => encrypt($this->kyc_number),
            ]);
            $bankAccountService->generateAccount($user);
            $this->dispatch('close-modal', name: 'create-bankAccount-modal');
            return $this->successAlert("Account generated successfully! kyc_number: {$this->kyc_number}, kyc_type: {$this->kyc_type}");
        } catch (Exception $exception) {
            Log::error('Failed to generate account: ' . $exception->getMessage());
            $this->dispatch('close-modal', name: 'create-bankAccount-modal');
            $this->errorAlert($exception->getMessage() ?: 'Unable to generate account at this time. Please try again later.');
        }
    }


    public function mount()
    {
        $user = Auth::user();
        $this->balance = $user->balance;
        $this->referralBalance = $user->bonus;

        // Populate active gateways based on system settings
        $this->gateways = [];
        $gatewaySettings = [
            'paypal' => 'paypal_payment',
            'flutterwave' => 'flutterwave_payment',
            'paystack' => 'paystack_payment',
            'korapay' => 'korapay_payment',
        ];

        foreach ($gatewaySettings as $gateway => $setting) {
            if (sys_setting($setting) == 1) {
                $this->gateways[$gateway] = $this->allGateways[$gateway];
            }
        }

        // check for bank account
        $this->hasAccount = $user->bankAccounts()->exists();
        $this->bankAccount = $user->bankAccounts()->first();
    }

    public function render()
    {
        $this->metaTitle = 'Wallet';
        return view('livewire.user.wallet');
    }
}
