<?php

namespace App\Livewire\Admin;

use App\Models\ApiProvider;
use App\Models\Order;
use App\Models\Transaction;
use App\Traits\LivewireToast;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
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
    public $deletingOrder = null;
    public $editingOrder = null;
    public $page = 'list';
    public $editData = [];

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

    public function bulkSetStatus($status)
    {
        if (empty($this->selectedOrders)) {
            $this->errorAlert('No orders selected');
            return;
        }

        $count = count($this->selectedOrders);

        try {
            DB::beginTransaction();

            foreach ($this->selectedOrders as $orderId) {
                $order = Order::with('user')->findOrFail($orderId);

                if (
                    in_array($status, ['canceled', 'partial', 'refunded']) &&
                    $order->remains > 0 &&
                    !in_array($order->status, ['canceled', 'completed', 'refunded'])
                ) {
                    $this->refundUserBalance($order);
                }

                $order->update(['status' => $status, 'updated_at' => now()]);

                if ($status === 'pending') {
                    $this->sendSingleOrderToApi($orderId);
                }
                if ($status === 'completed') {
                    $order->update([
                        'remains' => 0,
                    ]);
                }
                $this->sendOrderUpdateNotification($order, $order->user);
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
                $order->update(['status' => 'refunded']);

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
                    'updated_at' => now(),
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
        $this->dispatch('viewOrder', orderId: $orderId);
    }

    public function editOrder($orderId)
    {
        $order = Order::with([
            'service:id,name,category_id',
            'service.category:id,name',
            'user:id,name,email',
            'apiProvider:id,name'
        ])->findOrFail($orderId);

        $this->editData = [
            'id' => $order->id,
            'link' => $order->link,
            'remains' => $order->remains,
            'start_counter' => $order->start_counter,
            'status' => $order->status,
            'note' => $order->note,
        ];

        $this->editingOrder = $order;
        $this->page = 'edit';
    }

    public function cancelEdit()
    {
        $this->editingOrder = null;
        $this->page = "list";
    }

    public function updateOrder()
    {
        $this->validate([
            'editData.link' => 'required|string|max:255',
            'editData.remains' => 'nullable|numeric|min:0',
            'editData.start_counter' => 'nullable|numeric|min:0',
            'editData.status' => 'required|in:pending,processing,inprogress,completed,partial,canceled,refunded,error,fail',
            'editData.note' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $order = $this->editingOrder;
            $user = $order->user;

            if (!$order || !$user) {
                throw new \Exception('Order or user not found.');
            }

            $oldRemains = $order->remains;
            $oldStatus = $order->status;
            $newStatus = $this->editData['status'];

            // Update basic fields
            $order->link = $this->editData['link'];
            $order->remains = $this->editData['remains'] ?? 0;
            $order->start_counter = $this->editData['start_counter'];
            $order->note = $this->editData['note'];
            $order->error = 0; // Reset error flag when manually updating
            $order->error_message = null;

            // Refund logic for cancel or partial status
            if (
                in_array($newStatus, ['canceled', 'partial', 'refunded']) &&
                $oldRemains > 0 &&
                !in_array($oldStatus, ['canceled', 'completed', 'refunded'])
            ) {
                $perOrder = $order->quantity > 0 ? ($order->price / $order->quantity) : 0;
                $refundAmount = $oldRemains * $perOrder;

                if ($refundAmount > 0) {
                    // Update user balance
                    $user->increment('balance', $refundAmount);

                    // Create transaction record
                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'credit',
                        'code' => getTrx(),
                        'service' => 'order',
                        'message' => 'Refund for order #{$order->id}',
                        'amount' => $refundAmount,
                        'image' => 'order.png',
                        'charge' => 0,
                        'new_balance' => $user->balance,
                        'old_balance' => $user->balance - $refundAmount,
                        'meta' => [
                            'service_id' => $order->service_id,
                            'order_id' => $order->id,
                        ],
                        'status' => 'successful',
                    ]);
                }
            }

            // Update status
            $order->status = $newStatus;
            if ($newStatus === 'completed') {
                $order->remains = 0;
            }
            $order->save();

            // Send notification to user
            $this->sendOrderUpdateNotification($order, $user);

            DB::commit();

            $this->successAlert('Order updated successfully');
            $this->page = 'list';
            $this->editingOrder = null;
            $this->loadStatusCounts();
        } catch (\Exception $e) {
            DB::rollback();
            $this->errorAlert('Failed to update order: ' . $e->getMessage());
        }
    }

    private function sendOrderUpdateNotification($order, $user)
    {
        try {
            // Email notification
            $subject = 'Order Status Updated';
            $message = view('emails.order-updated', [
                'order' => $order,
                'user' => $user
            ])->render();

            // You can replace this with your actual email sending method
            // general_email($user->email, $subject, $message);

            // In-app notification (if you have a notification system)
            $user->notifications()->create([
                'title' => 'Order Status Updated',
                'message' => "Your Order #{$order->id} status has been updated to: " . ucfirst($order->status),
                'type' => 'order_update',
                'data' => [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'remains' => $order->remains,
                ],
            ]);
        } catch (\Exception $e) {
            // Log notification error but don't fail the main operation
            \Log::warning('Failed to send order update notification: ' . $e->getMessage());
        }
    }

    public function deleteModal($orderId)
    {
        $this->deletingOrder = Order::findOrFail($orderId);
        $this->dispatch('open-modal', name: 'delete-order-modal');
    }

    public function deleteOrder()
    {
        try {
            $this->deletingOrder->delete();
            $this->successAlert('Order deleted successfully');
            $this->dispatch('close-modal', name: 'delete-order-modal');
            $this->deletingOrder = null;
            $this->loadStatusCounts();
        } catch (\Exception $e) {
            $this->errorAlert('Failed to delete order: ' . $e->getMessage());
        }
    }

    public function closeDeleteModal(): void
    {
        $this->dispatch('close-modal', name: 'delete-order-modal');
        $this->deletingOrder = null;
    }

    public function viewResponse($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->response) {
            $this->selectedOrderId = $orderId;
            $this->selectedOrderResponse = $order->response;
            $this->dispatch('open-modal', name: 'order-response-modal');
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
            'error_message' => null,
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
