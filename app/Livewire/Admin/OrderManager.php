<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\ApiProvider;
use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;
use Str;

#[Layout('admin.layouts.main')]
class OrderManager extends Component
{
    use LivewireToast, WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $status = 'all';

    #[Url(history: true)]
    public $provider = 'all';

    #[Url(history: true)]
    public $serviceType = 'all';

    #[Url(history: true)]
    public $perPage = 50;

    // Bulk selection
    public $selectedOrders = [];
    public $selectAll = false;

    // Modals
    public $showResponseModal = false;
    public $selectedOrderResponse = null;
    public $selectedOrderId = null;

    protected $queryString = ['search', 'status', 'provider', 'serviceType', 'perPage'];

    public $statuses = [
        'all',
        'pending',
        'inprogress',
        'processing',
        'partial',
        'completed',
        'canceled',
        'refunded',
        'fail',
    ];

    // Cache for counts and options
    public $statusCounts = [];
    public $providers = [];
    public $serviceTypes = [];

    // Meta properties
    public string $metaTitle = 'Order Management';
    public string $metaDescription = 'Manage and monitor all orders';
    public string $metaKeywords;
    public string $metaImage;

    public function mount()
    {
        $this->loadStatusCounts();
        $this->loadFilterOptions();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedOrders = $this->getFilteredOrderIds();
        } else {
            $this->selectedOrders = [];
        }
    }

    public function updatedSelectedOrders()
    {
        $this->selectAll = count($this->selectedOrders) === count($this->getFilteredOrderIds());
    }

    private function getFilteredOrderIds()
    {
        $query = Order::query();
        $this->applyFilters($query);
        return $query->pluck('id')->toArray();
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->metaTitle = $status === 'all' ? 'Order Management' : Str::title($status) . ' Orders';
        $this->resetPage();
        $this->resetSelection();
    }

    public function updateProvider($provider)
    {
        $this->provider = $provider;
        $this->resetPage();
        $this->resetSelection();
    }

    public function updateServiceType($serviceType)
    {
        $this->serviceType = $serviceType;
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->status = 'all';
        $this->provider = 'all';
        $this->serviceType = 'all';
        $this->metaTitle = 'Order Management';
        $this->resetPage();
        $this->resetSelection();
    }

    private function resetSelection()
    {
        $this->selectedOrders = [];
        $this->selectAll = false;
    }

    // Bulk Actions
    public function bulkSetStatus($status)
    {
        if (empty($this->selectedOrders)) {
            $this->errorAlert('No orders selected');
            return;
        }

        $count = count($this->selectedOrders);

        try {
            DB::beginTransaction();

            if ($status === 'pending' && $count === 1) {
                // Single order - send to API directly (placeholder)
                $this->sendSingleOrderToApi($this->selectedOrders[0]);
            } else {
                // Bulk update - just set status
                Order::whereIn('id', $this->selectedOrders)->update([
                    'status' => $status,
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            $this->successAlert("Successfully updated {$count} orders to {$status}");
            $this->resetSelection();
            $this->loadStatusCounts();
        } catch (\Exception $e) {
            DB::rollback();
            $this->errorAlert('Failed to update orders: ' . $e->getMessage());
        }
    }

    public function bulkCancelAndRefund()
    {
        if (empty($this->selectedOrders)) {
            $this->errorAlert('No orders selected');
            return;
        }

        $count = count($this->selectedOrders);

        try {
            DB::beginTransaction();

            $orders = Order::with('user')->whereIn('id', $this->selectedOrders)->get();

            foreach ($orders as $order) {
                // Update order status
                $order->update(['status' => 'refunded']);

                // Refund user balance (placeholder - implement your refund logic)
                $this->refundUserBalance($order);
            }

            DB::commit();
            $this->successAlert("Successfully canceled and refunded {$count} orders");
            $this->resetSelection();
            $this->loadStatusCounts();
        } catch (\Exception $e) {
            DB::rollback();
            $this->errorAlert('Failed to cancel and refund orders: ' . $e->getMessage());
        }
    }

    public function bulkResend()
    {
        if (empty($this->selectedOrders)) {
            $this->errorAlert('No orders selected');
            return;
        }

        $count = count($this->selectedOrders);

        try {
            if ($count === 1) {
                $this->sendSingleOrderToApi($this->selectedOrders[0]);
                $this->successAlert('Order resent to API');
            } else {
                Order::whereIn('id', $this->selectedOrders)->update([
                    'status' => 'pending',
                    'error' => 0,
                    'error_message' => null,
                    'updated_at' => now()
                ]);
                $this->successAlert("Successfully queued {$count} orders for resending");
            }

            $this->resetSelection();
            $this->loadStatusCounts();
        } catch (\Exception $e) {
            $this->errorAlert('Failed to resend orders: ' . $e->getMessage());
        }
    }

    // Individual Actions
    public function viewOrder($orderId)
    {
        // Redirect to order details page or emit event
        $this->dispatch('viewOrder', orderId: $orderId);
    }

    public function editOrder($orderId)
    {
        // Redirect to edit page or emit event
        $this->dispatch('editOrder', orderId: $orderId);
    }

    public function deleteOrder($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            // $order->delete();
            $this->successAlert('Order deleted successfully');
            $this->loadStatusCounts();
        } catch (\Exception $e) {
            $this->errorAlert('Failed to delete order: ' . $e->getMessage());
        }
    }

    public function viewResponse($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->response) {
            $this->selectedOrderId = $orderId;
            $this->selectedOrderResponse = $order->response;
            $this->showResponseModal = true;
        } else {
            $this->errorAlert('No response data available for this order');
        }
    }


    public function resendOrder($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            
            if ($order->error == 1 || in_array($order->status, ['error', 'fail'])) {
                $this->sendSingleOrderToApi($orderId);
                $this->successAlert('Order resent to API');
            } else {
                $this->errorAlert('Order is not in error state');
            }
        } catch (\Exception $e) {
            $this->errorAlert('Failed to resend order: ' . $e->getMessage());
        }
    }

    // Helper Methods
    private function sendSingleOrderToApi($orderId)
    {
        // Placeholder for API sending logic
        $order = Order::findOrFail($orderId);
        $order->update([
            'status' => 'pending',
            'error' => 0,
            'error_message' => null
        ]);
        
        // TODO: Implement actual API sending logic here
        // $this->dispatchApiOrder($order);
    }

    private function refundUserBalance($order)
    {
        // Placeholder for refund logic
        if ($order->user) {
            // TODO: Implement your refund logic
            // $order->user->increment('balance', $order->price);
        }
    }

    private function loadStatusCounts()
    {
        $baseQuery = Order::query();

        foreach ($this->statuses as $statusItem) {
            if ($statusItem === 'all') {
                continue;
            }
            $this->statusCounts[$statusItem] = (clone $baseQuery)->where('status', $statusItem)->count();
        }
    }

    private function loadFilterOptions()
    {
        // Load providers
        $this->providers = ApiProvider::select('id', 'name')
            ->whereHas('orders')
            ->orderBy('name')
            ->get()
            ->toArray();

        // Load service types
        $this->serviceTypes = Order::select('service_type')
            ->distinct()
            ->whereNotNull('service_type')
            ->where('service_type', '!=', '')
            ->orderBy('service_type')
            ->pluck('service_type')
            ->toArray();
    }

    private function applyFilters($query)
    {
        // Apply status filter
        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // Apply provider filter
        if ($this->provider && $this->provider !== 'all') {
            $query->where('api_provider_id', $this->provider);
        }

        // Apply service type filter
        if ($this->serviceType && $this->serviceType !== 'all') {
            $query->where('service_type', $this->serviceType);
        }

        // Apply search filter
        if ($this->search) {
            $query->search('%' . $this->search . '%');
        }
    }

    public function render()
    {
        $query = Order::with(['service', 'user', 'apiProvider']);
        $this->applyFilters($query);
        $orders = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.order-manager', [
            'orders' => $orders,
        ]);
    }
}