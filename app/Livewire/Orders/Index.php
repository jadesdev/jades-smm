<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use App\Traits\LivewireToast;
use Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('user.layouts.main')]
class Index extends Component
{
    use LivewireToast, WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $status = 'all';

    #[Url(history: true)]
    public $perPage = 50;

    protected $queryString = ['search', 'status', 'perPage'];

    public $statuses = [
        'all',
        'pending',
        'partial',
        'completed',
        'processing',
        'inprogress',
        'canceled',
    ];

    // Cache for status counts
    public $statusCounts = [];

    // Meta properties
    public string $metaTitle = 'Order History';

    public string $metaDescription = 'Order History';

    public string $metaKeywords;

    public string $metaImage;

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->metaTitle = $status === 'all' ? 'Order History' : Str::title($status).' Orders';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->status = 'all';
        $this->metaTitle = 'Order History';
        $this->resetPage();
    }

    public function mount()
    {
        $this->loadStatusCounts();
    }

    private function loadStatusCounts()
    {
        $baseQuery = Order::where('user_id', Auth::id())
            ->where('is_drip_feed', false)
            ->where('service_type', '!=', 'subscriptions');

        // Get counts for each status
        foreach ($this->statuses as $statusItem) {
            if ($statusItem === 'all') {
                continue;
            }

            $this->statusCounts[$statusItem] = (clone $baseQuery)
                ->where('status', $statusItem)
                ->count();
        }
    }

    public function render()
    {
        $query = Order::where('user_id', Auth::id())
            ->with('service')
            ->where('is_drip_feed', false)
            ->where('service_type', '!=', 'subscriptions');

        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%'.$this->search.'%')
                    ->orWhereHas('service', function ($serviceQuery) {
                        $serviceQuery->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhere('link', 'like', '%'.$this->search.'%');
            });
        }

        $orders = $query->latest()->paginate($this->perPage);

        return view('livewire.orders.index', [
            'orders' => $orders,
        ]);
    }
}
