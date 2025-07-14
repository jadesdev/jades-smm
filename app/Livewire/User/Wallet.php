<?php

namespace App\Livewire\User;

use App\Models\Transaction;
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
    use LivewireToast, WithPagination;

    public $tab = 'deposit';

    public $amount = '';

    public $selectedGateway = '';

    public $showBalance = true;

    public $balance = 0;

    public $referralBalance = 0;

    // Transaction filters
    public $search = '';

    public $statusFilter = '';

    public $typeFilter = '';

    public $perPage = 30;

    protected $queryString = ['tab'];

    // meta
    public string $metaTitle = 'Wallet';

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public bool $processingPayment = false;

    public $gateways = [
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
        'crypto' => [
            'name' => 'Crypto',
            'fee' => '0.00%',
            'icon' => 'fab fa-bitcoin',
            'image' => 'cryptomus.png',
        ],
    ];

    public function getAvailableServicesProperty()
    {
        $services = Transaction::where('user_id', Auth::id())
            ->select('service')
            ->distinct()
            ->pluck('service')
            ->mapWithKeys(fn ($service) => [$service => ucfirst($service)])
            ->toArray();

        return ['' => 'All Services'] + $services;
    }

    public function getTransactionsProperty()
    {
        $filtered = Transaction::where('user_id', Auth::id())->orderBy('updated_at', 'desc');

        // Search filter
        if ($this->search) {
            $filtered = $filtered->where(function ($query) {
                $query->where('code', 'LIKE', "%{$this->search}%")
                    ->orWhere('message', 'LIKE', "%{$this->search}%")
                    ->orWhere('id', 'LIKE', "%{$this->search}%")
                    ->orWhere('gateway', 'LIKE', "%{$this->search}%");
            });
        }

        // Type filter
        if ($this->typeFilter) {
            $filtered = $filtered->where('service', $this->typeFilter);
        }

        // Status filter
        if ($this->statusFilter) {
            $filtered = $filtered->where('status', $this->statusFilter);
        }

        return $filtered->paginate($this->perPage);
    }

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

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'typeFilter']);
    }

    public function loadMore()
    {
        $this->perPage += 10;
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
            $depositService = app(DepositService::class);

            return $depositService->initiateDeposit(
                amount: (float) $this->amount,
                gateway: $this->selectedGateway,
                user: Auth::user()
            );
            $this->successAlert('Deposit successful!');
        } catch (Exception $exception) {
            Log::error('Deposit failed: '.$exception->getMessage());
            $this->errorAlert($exception->getMessage() ?: 'Unable to process your payment at this time. Please try again later.');
        }
    }

    public function getDepositButtonTextProperty()
    {
        if ($this->amount) {
            return 'Deposit ₦'.number_format(floatval($this->amount), 2);
        }

        return 'Deposit ₦0.00';
    }

    public function getIsDepositValidProperty()
    {
        return ! empty($this->amount) && ! empty($this->selectedGateway) && floatval($this->amount) > 0;
    }

    public function changeTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $user = Auth::user();
        $this->balance = $user->balance;
        $this->referralBalance = $user->bonus;
    }

    public function render()
    {
        $this->metaTitle = 'Wallet';
        if ($this->tab === 'transactions') {
            $this->metaTitle = 'Transactions';
        }

        return view('livewire.user.wallet');
    }
}
