<?php

namespace App\Livewire\Orders;

use App\Traits\LivewireToast;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Index extends Component
{
    use LivewireToast;

    public $status = 'all';

    public $statuses = [
        'all',
        'pending',
        'partial',
        'completed',
        'processing',
        'in progress',
        'canceled',
    ];

    public $search = '';

    protected $queryString = ['search', 'status'];

    // meta
    public string $metaTitle = 'Order History';

    public string $metaDescription = 'Order History';

    public string $metaKeywords;

    public string $metaImage;

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->metaTitle = Str::title($status).' Orders';
    }

    public function mount($status = 'all')
    {
        $this->updateStatus($status);
    }

    public function render()
    {
        return view('livewire.orders.index');
    }
}
