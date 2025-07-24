<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

#[Layout('admin.layouts.main')]
class Transactions extends Component
{
    use LivewireToast, WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $statusFilter = '';

    #[Url(except: '')]
    public string $typeFilter = '';

    #[Url(except: '')]
    public string $serviceFilter = '';

    #[Url(as: 'user_id', except: '')]
    public string $userFilter = '';

    public int $perPage = 25;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public ?Transaction $viewingTransaction = null;

    // Meta
    public string $metaTitle = "Transactions";

    public function mount()
    {
        $this->viewingTransaction = Transaction::query()->first();
        $this->deleteOldTransactions();
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['search', 'statusFilter', 'typeFilter', 'serviceFilter', 'perPage'])) {
            $this->resetPage();
        }
    }

    /**
     * Sort by a given table field.
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Clear all filters and search.
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'typeFilter', 'serviceFilter', 'perPage']);
        $this->resetPage();
    }

    /**
     * Load a transaction into the modal.
     */
    public function showDetails(Transaction $transaction): void
    {
        $this->viewingTransaction = $transaction;
        $this->dispatch('open-modal', name: 'transaction-details');
    }

    public function approve(Transaction $transaction)
    {
        $transaction->update(['status' => 'successful']);
        $this->successAlert("Transaction #{$transaction->code} approved.");
        $this->viewingTransaction = null;
        $this->dispatch('close-modal', name: 'transaction-details');
    }

    public function reverse(Transaction $transaction)
    {
        $transaction->update(['status' => 'reversed']);
        $this->successAlert("Transaction #{$transaction->code} reversed.");
        $this->viewingTransaction = null;
        $this->dispatch('close-modal', name: 'transaction-details');
    }
    
    private function deleteOldTransactions()
    {
        Transaction::where('status', 'initiated')
            ->where('created_at', '<', now()->subWeek())
            ->delete();
    }
    

    public function render()
    {
        $query = Transaction::query()
            ->with('user')
            ->where('status', '!=', 'initiated')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('code', 'like', "%{$this->search}%")
                        ->orWhere('service', 'like', "%{$this->search}%")
                        ->orWhere('message', 'like', "%{$this->search}%")
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', "%{$this->search}%")
                                ->orWhere('email', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->userFilter, fn($q) => $q->where('user_id', $this->userFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->serviceFilter, fn($q) => $q->where('service', $this->serviceFilter));

        $transactions = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);

        $statuses = ['successful', 'pending', 'failed', 'processing', 'reversed'];
        $types = ['credit', 'debit'];
        $services = Transaction::query()->select('service')->whereNotNull('service')->distinct()->pluck('service')->toArray();

        return view('livewire.admin.transactions', [
            'transactions' => $transactions,
            'statuses' => array_combine($statuses, array_map('ucfirst', $statuses)),
            'types' => array_combine($types, array_map('ucfirst', $types)),
            'services' => array_combine($services, array_map('ucfirst', $services)),
        ]);
    }
}
