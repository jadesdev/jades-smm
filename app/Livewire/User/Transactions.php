<?php

namespace App\Livewire\User;

use App\Models\Transaction;
use App\Traits\LivewireToast;
use Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.main')]
class Transactions extends Component
{
    use LivewireToast, WithPagination;

    public $totalCredits;

    public $totalDebits;

    public $totalTransactions;

    // Transaction filters
    public $search;

    public $statusFilter;

    public $typeFilter;

    public $perPage = 30;

    // meta
    public string $metaTitle = 'Transactions';

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
        $filtered = Transaction::where('user_id', Auth::id())
            ->where('status', '!=', 'initiated')
            ->orderBy('updated_at', 'desc');

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

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'typeFilter']);
    }

    public function loadMore()
    {
        $this->perPage += intval($this->perPage);
    }

    public function mount()
    {
        $this->loadQuickStats();
    }

    public function loadQuickStats()
    {
        $userId = Auth::id();

        $this->totalCredits = Transaction::where('user_id', $userId)
            ->where('type', 'credit')
            ->where('status', 'successful')
            ->sum('amount');

        $this->totalDebits = Transaction::where('user_id', $userId)
            ->where('type', 'debit')
            ->where('status', 'successful')
            ->sum('amount');

        $this->totalTransactions = Transaction::where('user_id', $userId)
            ->where('status', '!=', 'initiated')
            ->count();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'statusFilter', 'typeFilter'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        return view('livewire.user.transactions');
    }
}
