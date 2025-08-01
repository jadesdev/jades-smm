<?php

namespace App\Livewire\Orders;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Category;
use App\Models\Service;
use App\Services\OrderService;
use App\Traits\LivewireToast;
use Auth;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Bulk extends Component
{
    use LivewireToast;

    public Collection $categories;

    public Collection $services;

    public ?int $category_id = null;

    public ?int $service_id = null;

    public array $orders = [];

    public string $bulk_order = '';

    public string $link = '';

    public ?int $quantity = null;

    // meta
    public string $metaTitle = 'Bulk Order';

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public ?Service $selectedService = null;

    public float $charge = 0.00;

    public float $userBalance = 0.00;

    public function mount()
    {
        $this->categories = Category::where('is_active', true)
            ->has('activeServices')
            ->orderBy('name')
            ->get();
        $this->services = collect();
        $this->userBalance = Auth::user()->balance ?? 0.00;

        // Initialize with one empty row
        $this->orders = [
            ['link' => '', 'quantity' => ''],
        ];

        $service_id = request()->get('service_id');
        if ($service_id) {
            $service = Service::find($service_id);
            if ($service) {
                $this->category_id = $service->category_id;
                $this->updatedCategoryId($service->category_id);
                $this->service_id = (int) $service_id;
                $this->updatedServiceId($service_id);
                $this->successAlert('Service selected successfully!');
            }
        }
    }

    public function updatedCategoryId($value)
    {
        $this->reset('service_id', 'selectedService', 'charge');

        if ($value) {
            $this->services = Service::where('category_id', $value)
                ->active()
                ->whereIn('type', ['default', 'comment_likes'])
                ->get();
        } else {
            $this->services = collect();
        }

        $this->calculateCharge();
    }

    public function updatedServiceId($value)
    {
        if ($value) {
            $this->selectedService = Service::find($value);
        } else {
            $this->selectedService = null;
        }

        $this->calculateCharge();
    }

    public function updatedOrders()
    {
        $this->calculateCharge();
    }

    public function addRow()
    {
        $this->orders[] = ['link' => '', 'quantity' => ''];
    }

    public function removeRow($index)
    {
        if (count($this->orders) > 1) {
            unset($this->orders[$index]);
            $this->orders = array_values($this->orders); // Re-index array
            $this->calculateCharge();
        }
    }

    public function calculateCharge()
    {
        $this->charge = 0.00;

        if (! $this->selectedService) {
            return;
        }

        foreach ($this->orders as $order) {
            if (! empty($order['quantity']) && is_numeric($order['quantity'])) {
                $quantity = (int) $order['quantity'];
                $this->charge += ($quantity / 1000) * $this->selectedService->price;
            }
        }
    }

    public function submit(OrderService $orderService)
    {
        $this->validate([
            'category_id' => 'required|exists:categories,id',
            'service_id' => 'required|exists:services,id',
            'orders' => 'required|array|min:1',
            'orders.*.link' => 'required|string|max:500',
            'orders.*.quantity' => 'required|integer|min:1',
        ]);

        // Check if user has sufficient balance
        if ($this->charge > $this->userBalance) {
            $this->errorAlert('Insufficient balance to place this order.');

            return;
        }

        $links = [];
        $quantities = [];

        $validOrders = collect($this->orders)->filter(function ($order) {
            return ! empty($order['link']) && ! empty($order['quantity']);
        });

        if ($validOrders->isEmpty()) {
            $this->errorAlert('Please add at least one valid order.');

            return;
        }

        foreach ($validOrders as $order) {
            $links[] = $order['link'];
            $quantities[] = (int) $order['quantity'];
        }

        $bulkData = [
            'links' => $links,
            'quantity' => $quantities,
        ];
        try {
            $result = $orderService->createBulkOrder(Auth::user(), $this->selectedService, $bulkData);

            if ($result['success']) {
                $this->successAlert("Created {$result['order_count']} orders for ".format_price($result['total_charge']));

                if (! empty($result['errors'])) {
                    foreach ($result['errors'] as $link => $error) {
                        $this->errorAlert("Error for {$link}: {$error}");
                    }
                }

                return $this->redirect(route('user.orders'), navigate: true);
            } else {
                $this->errorAlert($result['message']);
            }
        } catch (InsufficientBalanceException $e) {
            $this->errorAlert($e->getMessage());
        } catch (\Exception $e) {
            $this->errorAlert('Failed to place order. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.orders.bulk');
    }
}
