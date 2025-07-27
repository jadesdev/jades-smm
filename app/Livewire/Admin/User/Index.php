<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Traits\LivewireToast;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.main')]
class Index extends Component
{
    use LivewireToast, WithPagination;

    public $search = '';

    public $statusFilter = '';

    public $countryFilter = '';

    public $emailVerifiedFilter = '';

    public $smsVerifiedFilter = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public $perPage = 50;

    // meta
    public string $metaTitle = 'All Users';

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCountryFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->countryFilter = '';
        $this->emailVerifiedFilter = '';
        $this->smsVerifiedFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('username', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->when($this->countryFilter, function ($query) {
                $query->where('country', $this->countryFilter);
            })
            ->when($this->emailVerifiedFilter !== '', function ($query) {
                if ($this->emailVerifiedFilter == '1') {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            })
            ->when($this->smsVerifiedFilter !== '', function ($query) {
                $query->where('sms_verify', $this->smsVerifiedFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.user.index', compact('users'));
    }
}
