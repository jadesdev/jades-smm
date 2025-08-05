<?php

namespace App\Livewire\Admin;

use App\Models\ApiProvider;
use App\Models\Order;
use App\Services\OrderService;
use App\Traits\LivewireToast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.main')]
class OrderManager extends Component
{
    use LivewireToast, WithPagination;

    // URL parameters for state persistence
    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $status = 'all';

    #[Url(history: true)]
    public string $provider = 'all';

    #[Url(history: true)]
    public string $serviceType = 'all';

    #[Url(history: true)]
    public int $perPage = 50;

    // Selection state
    public array $selectedOrders = [];
    public bool $selectAll = false;

    // Modal state
    public bool $showResponseModal = false;
    public ?string $selectedOrderResponse = null;
    public ?int $selectedOrderId = null;
    public ?Order $deletingOrder = null;
    public ?Order $editingOrder = null;

    // Page state
    public string $page = 'list';
    public array $editData = [];

    // Cached data
    public array $statusCounts = [];
    public array $providers = [];
    public array $serviceTypes = [];

    // Constants
    public const STATUSES = [
        'all',
        'pending',
        'inprogress',
        'processing',
        'partial',
        'completed',
        'canceled',
        'refunded',
        'fail'
    ];

    public const REFUNDABLE_STATUSES = ['canceled', 'partial', 'refunded'];
    public const NON_REFUNDABLE_STATUSES = ['canceled', 'completed', 'refunded'];

    // Meta properties
    public string $metaTitle = 'Order Management';
    public string $metaDescription = 'Manage and monitor all orders';

    protected $queryString = ['search', 'status', 'provider', 'serviceType', 'perPage'];

    public function mount(): void
    {
        $this->loadCachedData();
    }

    // Selection Management
    public function updatedSelectAll(bool $value): void
    {
        $this->selectedOrders = $value ? $this->getFilteredOrderIds() : [];
    }

    public function updatedSelectedOrders(): void
    {
        $filteredIds = $this->getFilteredOrderIds();
        $this->selectAll = count($this->selectedOrders) === count($filteredIds);
    }

    private function getFilteredOrderIds(): array
    {
        return Cache::remember(
            $this->getCacheKey('filtered_ids'),
            300, // 5 minutes
            fn() => $this->buildFilteredQuery()->pluck('id')->toArray()
        );
    }

    // Filter Updates
    public function updateStatus(string $status): void
    {
        $this->validateStatus($status);
        $this->status = $status;
        $this->updateMetaTitle($status);
        $this->resetFilters();
    }

    public function updateProvider(string $provider): void
    {
        $this->provider = $provider;
        $this->resetFilters();
    }

    public function updateServiceType(string $serviceType): void
    {
        $this->serviceType = $serviceType;
        $this->resetFilters();
    }

    public function updatedSearch(): void
    {
        $this->resetFilters();
    }

    public function updatedPerPage(): void
    {
        $this->resetFilters();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'provider', 'serviceType']);
        $this->status = 'all';
        $this->metaTitle = 'Order Management';
        $this->resetFilters();
    }

    private function resetFilters(): void
    {
        $this->resetPage();
        $this->resetSelection();
        $this->clearFilterCache();
    }

    private function resetSelection(): void
    {
        $this->selectedOrders = [];
        $this->selectAll = false;
    }

    // Bulk Operations
    public function bulkSetStatus(string $status): void
    {
        $this->validateSelectedOrders();
        $this->validateStatus($status);

        $count = count($this->selectedOrders);

        DB::beginTransaction();
        try {
            $orders = Order::with('user')->whereIn('id', $this->selectedOrders)->get();

            foreach ($orders as $order) {
                $this->processStatusUpdate($order, $status);
            }

            DB::commit();
            $this->handleBulkSuccess("Successfully updated {$count} orders to {$status}");
        } catch (\Exception $e) {
            DB::rollback();
            $this->handleBulkError('Failed to update orders', $e);
        }
    }

    public function bulkCancelAndRefund(): void
    {
        $this->validateSelectedOrders();

        $count = count($this->selectedOrders);

        DB::beginTransaction();
        try {
            $orders = Order::with('user')->whereIn('id', $this->selectedOrders)->get();

            foreach ($orders as $order) {
                $this->processRefund($order);
                $order->update(['status' => 'refunded']);
            }

            DB::commit();
            $this->handleBulkSuccess("Successfully canceled and refunded {$count} orders");
        } catch (\Exception $e) {
            DB::rollback();
            $this->handleBulkError('Failed to cancel and refund orders', $e);
        }
    }

    public function bulkResend(): void
    {
        $this->validateSelectedOrders();

        $count = count($this->selectedOrders);

        try {
            if ($count === 1) {
                app(OrderService::class)->resendOrder($this->selectedOrders[0]);
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

            $this->handleBulkSuccess();
        } catch (\Exception $e) {
            $this->handleBulkError('Failed to resend orders', $e);
        }
    }

    // Individual Order Actions
    public function viewOrder(int $orderId): void
    {
        $this->dispatch('viewOrder', orderId: $orderId);
    }

    public function editOrder(int $orderId): void
    {
        $order = Order::with([
            'service:id,name,category_id',
            'service.category:id,name',
            'user:id,name,email',
            'provider:id,name',
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

    public function cancelEdit(): void
    {
        $this->reset(['editingOrder', 'editData']);
        $this->page = 'list';
    }

    public function updateOrder(): void
    {
        $this->validateEditData();

        DB::beginTransaction();
        try {
            $order = $this->editingOrder;
            $user = $order->user;

            if (!$order || !$user) {
                throw new \InvalidArgumentException('Order or user not found.');
            }

            $this->processOrderUpdate($order, $user);

            DB::commit();
            $this->successAlert('Order updated successfully');
            $this->cancelEdit();
            $this->loadCachedData();
        } catch (\Exception $e) {
            DB::rollback();
            $this->errorAlert('Failed to update order: ' . $e->getMessage());
            Log::error('Order update failed', [
                'order_id' => $this->editingOrder?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function deleteModal(int $orderId): void
    {
        $this->deletingOrder = Order::findOrFail($orderId);
        $this->dispatch('open-modal', name: 'delete-order-modal');
    }

    public function deleteOrder(): void
    {
        try {
            $this->deletingOrder->delete();
            $this->successAlert('Order deleted successfully');
            $this->closeDeleteModal();
            $this->loadCachedData();
        } catch (\Exception $e) {
            $this->errorAlert('Failed to delete order: ' . $e->getMessage());
        }
    }

    public function closeDeleteModal(): void
    {
        $this->dispatch('close-modal', name: 'delete-order-modal');
        $this->deletingOrder = null;
    }

    public function viewResponse(int $orderId): void
    {
        $order = Order::find($orderId);

        if (!$order || !$order->response) {
            $this->errorAlert('No response data available for this order');
            return;
        }

        $this->selectedOrderId = $orderId;
        $this->selectedOrderResponse = $order->response;
        $this->dispatch('open-modal', name: 'order-response-modal');
    }

    public function resendOrder(int $orderId): void
    {
        try {
            $order = Order::findOrFail($orderId);

            if (!$this->canResendOrder($order)) {
                $this->errorAlert('Order is not in error state');
                return;
            }

            app(OrderService::class)->resendOrder($orderId);
            $this->successAlert('Order resent to API');
        } catch (\Exception $e) {
            $this->errorAlert('Failed to resend order: ' . $e->getMessage());
        }
    }

    // Helper Methods
    private function validateSelectedOrders(): void
    {
        if (empty($this->selectedOrders)) {
            $this->errorAlert('No orders selected');
            throw new \InvalidArgumentException('No orders selected');
        }
    }

    private function validateStatus(string $status): void
    {
        if (!in_array($status, self::STATUSES)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }
    }

    private function validateEditData(): void
    {
        $this->validate([
            'editData.link' => 'required|string|max:255',
            'editData.remains' => 'nullable|numeric|min:0',
            'editData.start_counter' => 'nullable|numeric|min:0',
            'editData.status' => ['required', Rule::in(array_slice(self::STATUSES, 1))], // Exclude 'all'
            'editData.note' => 'nullable|string|max:1000',
        ]);
    }

    private function processStatusUpdate(Order $order, string $status): void
    {
        if ($this->shouldRefund($order, $status)) {
            $this->processRefund($order);
        }
        $oldStatus = $order->status;
        $order->update(['status' => $status, 'updated_at' => now()]);

        if ($status === 'pending') {
            app(OrderService::class)->resendOrder($order->id);
        }

        if ($status === 'completed') {
            $order->update(['remains' => 0]);
            $this->sendOrderCompletedNotification($order, $order->user);
        }

        if ($status !== $oldStatus && $status !== 'completed') {
            $this->sendOrderUpdateNotification($order, $order->user);
        }
    }

    private function processOrderUpdate(Order $order, $user): void
    {
        $oldRemains = $order->remains;
        $oldStatus = $order->status;
        $newStatus = $this->editData['status'];

        // Update basic fields
        $order->fill([
            'link' => $this->editData['link'],
            'remains' => $this->editData['remains'] ?? 0,
            'start_counter' => $this->editData['start_counter'],
            'note' => $this->editData['note'],
            'status' => $newStatus,
            'error' => 0,
            'error_message' => null,
        ]);

        // Handle refunds if necessary
        if ($this->shouldRefundForStatusChange($oldStatus, $newStatus, $oldRemains)) {
            $this->processRefundForUpdate($order, $user, $oldRemains);
        }

        if ($newStatus === 'completed') {
            $order->remains = 0;
            $this->sendOrderCompletedNotification($order, $user);
        }

        $order->save();

        // Send notification for status changes (except completed, which is handled above)
        if ($newStatus !== $oldStatus && $newStatus !== 'completed') {
            $this->sendOrderUpdateNotification($order, $user);
        }
    }

    private function shouldRefund(Order $order, string $status): bool
    {
        return in_array($status, self::REFUNDABLE_STATUSES) &&
            $order->remains > 0 &&
            !in_array($order->status, self::NON_REFUNDABLE_STATUSES);
    }

    private function shouldRefundForStatusChange(string $oldStatus, string $newStatus, float $oldRemains): bool
    {
        return in_array($newStatus, self::REFUNDABLE_STATUSES) &&
            $oldRemains > 0 &&
            !in_array($oldStatus, self::NON_REFUNDABLE_STATUSES);
    }

    private function processRefund(Order $order): void
    {
        $order->load('user');
        if (!$order->user || $order->remains <= 0) {
            return;
        }

        $refundAmount = $this->calculateRefundAmount($order);

        if ($refundAmount > 0) {
            app(OrderService::class)->processRefund($order, $refundAmount);
        }
    }

    private function processRefundForUpdate(Order $order, $user, float $oldRemains): void
    {
        $refundAmount = $this->calculateRefundAmount($order, $oldRemains);

        if ($refundAmount > 0) {
            app(OrderService::class)->processRefund($order, $refundAmount);
        }
    }

    private function calculateRefundAmount(Order $order, ?float $remains = null): float
    {
        $remainsToRefund = $remains ?? $order->remains;
        $perOrder = $order->quantity > 0 ? ($order->price / $order->quantity) : 0;
        return $remainsToRefund * $perOrder;
    }

    private function canResendOrder(Order $order): bool
    {
        return $order->error == 1 || in_array($order->status, ['error', 'fail']);
    }

    private function handleBulkSuccess(?string $message = null): void
    {
        if ($message) {
            $this->successAlert($message);
        }
        $this->resetSelection();
        $this->loadCachedData();
    }

    private function handleBulkError(string $message, \Exception $e): void
    {
        $this->errorAlert($message . ': ' . $e->getMessage());
        Log::error($message, [
            'selected_orders' => $this->selectedOrders,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    private function updateMetaTitle(string $status): void
    {
        $this->metaTitle = $status === 'all'
            ? 'Order Management'
            : ucfirst($status) . ' Orders';
    }

    private function sendOrderUpdateNotification(Order $order, $user): void
    {
        sendNotification('ORDER_STATUS_CHANGE', $user, [
            'name' => $user->name,
            'order_id' => $order->id,
            'service_name' => $order->service?->name,
            'remains' => $order->remains,
            'order_status' => ucfirst($order->status),
            'refund_amount' => format_price($order->price),
        ]);
    }

    private function sendOrderCompletedNotification(Order $order, $user): void
    {
        sendNotification('ORDER_COMPLETED', $user, [
            'name' => $user->name,
            'order_id' => $order->id,
            'service_name' => $order->service?->name,
            'order_quantity' => $order->quantity,
            'remains' => $order->remains,
            'delivered' => $order->quantity - $order->remains,
            'completion_date' => $order->updated_at,
            'order_amount' => format_price($order->price),
        ]);
    }

    // Data Loading and Caching
    private function loadCachedData(): void
    {
        $this->loadStatusCounts();
        $this->loadFilterOptions();
    }

    private function loadStatusCounts(): void
    {
        $this->statusCounts = Cache::remember(
            $this->getCacheKey('status_counts'),
            600, // 10 minutes
            function () {
                $counts = [];
                $baseQuery = Order::query();

                foreach (array_slice(self::STATUSES, 1) as $status) { // Skip 'all'
                    $counts[$status] = (clone $baseQuery)->where('status', $status)->count();
                }

                return $counts;
            }
        );
    }

    private function loadFilterOptions(): void
    {
        $cacheKey = $this->getCacheKey('filter_options');

        $data = Cache::remember($cacheKey, 1800, function () { // 30 minutes
            return [
                'providers' => ApiProvider::select('id', 'name')
                    ->whereHas('orders')
                    ->orderBy('name')
                    ->get()
                    ->toArray(),
                'serviceTypes' => Order::select('service_type')
                    ->distinct()
                    ->whereNotNull('service_type')
                    ->where('service_type', '!=', '')
                    ->orderBy('service_type')
                    ->pluck('service_type')
                    ->toArray()
            ];
        });

        $this->providers = $data['providers'];
        $this->serviceTypes = $data['serviceTypes'];
    }

    private function buildFilteredQuery(): Builder
    {
        $query = Order::query();

        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        if ($this->provider && $this->provider !== 'all') {
            $query->where('api_provider_id', $this->provider);
        }

        if ($this->serviceType && $this->serviceType !== 'all') {
            $query->where('service_type', $this->serviceType);
        }

        if ($this->search) {
            $query->search('%' . $this->search . '%');
        }

        return $query;
    }

    private function getCacheKey(string $suffix): string
    {
        return "order_manager_{$suffix}_" . md5(serialize([
            $this->search,
            $this->status,
            $this->provider,
            $this->serviceType
        ]));
    }

    private function clearFilterCache(): void
    {
        $keys = ['status_counts', 'filter_options', 'filtered_ids'];
        foreach ($keys as $key) {
            Cache::forget($this->getCacheKey($key));
        }
    }

    public function render()
    {
        $query = Order::with(['service', 'user', 'provider']);
        $query = $this->buildFilteredQuery()->with(['service', 'user', 'provider']);
        $orders = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.order-manager', [
            'orders' => $orders,
            'statuses' => self::STATUSES,
        ]);
    }
}
