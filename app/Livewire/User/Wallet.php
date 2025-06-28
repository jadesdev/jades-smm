<?php

namespace App\Livewire\User;

use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('user.layouts.main')]
class Wallet extends Component
{
    use LivewireToast;

    public $activeTab = 'deposit';
    public $amount = '';
    public $selectedGateway = '';
    public $showBalance = true;
    public $balance = 1425.75;
    public $referralBalance = 425.75;

    // Transaction filters
    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $perPage = 10;

    public $gateways = [
        'paypal' => [
            'name' => 'PayPal',
            'fee' => '0.00%',
            'icon' => 'fab fa-paypal'
        ],
        'flutterwave' => [
            'name' => 'Flutterwave',
            'fee' => '0.00%',
            'icon' => 'fab fa-paypal' // You might want to change this
        ],
        'stripe' => [
            'name' => 'Stripe',
            'fee' => '0.00%',
            'icon' => 'fab fa-stripe'
        ]
    ];
    public function getTransactionsProperty()
    {
        return $transactions =  [
            [
                'id' => '123456789',
                'type' => 'deposit',
                'amount' => 102,
                'status' => 'successful',
                'fee' => 0.00,
                'date' => '2025-12-13',
                'description' => 'Just a random message about the transaction. can be short or long'
            ],
            [
                'id' => '987654321',
                'type' => 'withdraw',
                'amount' => 150,
                'status' => 'pending',
                'fee' => 5.00,
                'date' => '2025-11-28',
                'description' => 'Just a random message about the transaction. can be short or long'
            ],
            [
                'id' => '123456789',
                'type' => 'chargeback',
                'amount' => -5,
                'status' => 'failed',
                'fee' => 0.00,
                'date' => '2025-12-05',
                'description' => 'Just a random message about the transaction. can be short or long'
            ],
            [
                'id' => '123456789',
                'type' => 'referral',
                'amount' => 25,
                'status' => 'successful',
                'fee' => 0.00,
                'date' => '2025-12-01',
                'description' => 'Just a random message about the transaction. can be short or long'
            ]
        ];
        // Apply filters
        $filtered = collect($transactions);

        // Search filter
        if ($this->search) {
            $filtered = $filtered->filter(function ($transaction) {
                return str_contains(strtolower($transaction['id']), strtolower($this->search)) ||
                    str_contains(strtolower($transaction['description']), strtolower($this->search)) ||
                    str_contains(strtolower($transaction['gateway']), strtolower($this->search));
            });
        }

        // Type filter
        if ($this->typeFilter) {
            $filtered = $filtered->filter(function ($transaction) {
                return $transaction['type'] === $this->typeFilter;
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $filtered = $filtered->filter(function ($transaction) {
                return $transaction['status'] === $this->statusFilter;
            });
        }

        // Paginate (simple slice for demo - use real pagination in production)
        return $filtered->take($this->perPage)->values()->all();
    }
    public function toggleBalance()
    {
        $this->showBalance = !$this->showBalance;
    }

    public function selectGateway($gateway)
    {
        $this->selectedGateway = $gateway;
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
        // This fires automatically when amount changes
        // You can add validation here if needed
        $this->amount = max(0, floatval($this->amount));
    }
    public function deposit()
    {
        // Validate
        $this->validate([
            'amount' => 'required|numeric|min:1',
            'selectedGateway' => 'required|in:paypal,flutterwave,stripe'
        ]);

        $this->successAlert('Deposit of $' . number_format($this->amount, 2) . ' via ' . $this->gateways[$this->selectedGateway]['name'] . ' initiated!');

        // Reset form
        $this->reset(['amount', 'selectedGateway']);
    }

    public function copyTxId($txId)
    {
        // Emit event to handle copying on frontend
        $this->dispatch('copy-to-clipboard', $txId);
    }

    public function getDepositButtonTextProperty()
    {
        if ($this->amount) {
            return 'Deposit $' . number_format(floatval($this->amount), 2);
        }
        return 'Deposit $0.00';
    }

    public function getIsDepositValidProperty()
    {
        return !empty($this->amount) && !empty($this->selectedGateway) && floatval($this->amount) > 0;
    }

    protected $queryString = ['activeTab'];
    // meta
    public string $metaTitle = 'Wallet';

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function mount() {}

    public function render()
    {
        $this->metaTitle = "Wallet";
        if ($this->activeTab === 'transactions') {
            $this->metaTitle = 'Transactions';
        }
        return view('livewire.user.wallet');
    }
}
