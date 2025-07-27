<?php

namespace App\Livewire\Admin\Support;

use App\Models\SupportTicket;
use App\Traits\LivewireToast;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.main')]
class Tickets extends Component
{
    use LivewireToast;
    use WithPagination;

    // Meta properties
    public string $metaTitle;

    public string $view;

    // Filter and search properties
    public $search = '';

    public $statusFilter = '';

    public $perPage = 20;

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
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

    public function mount($type = 'all')
    {
        $this->view = $type;
        $this->metaTitle = 'Support Tickets - '.ucfirst($type);
    }

    public function getTicketsProperty()
    {
        $query = SupportTicket::with(['user', 'latestMessage']);

        switch ($this->view) {
            case 'open':
                $query->where('status', 'open');
                break;
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'resolved':
                $query->where('status', 'resolved');
                break;
            case 'closed':
                $query->where('status', 'closed');
                break;
            case 'all':
            default:
                break;
        }

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('code', 'like', '%'.$this->search.'%')
                    ->orWhere('subject', 'like', '%'.$this->search.'%')
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('email', 'like', '%'.$this->search.'%');
                    });
            });
        }

        if (! empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function deleteTicket($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();
        $this->successAlert('Ticket deleted successfully');
    }

    public function render()
    {
        return view('livewire.admin.support.tickets', [
            'tickets' => $this->tickets,
        ]);
    }
}
