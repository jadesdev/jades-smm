<?php

namespace App\Livewire\Orders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Traits\LivewireToast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Create extends Component
{
    use LivewireToast;

    public Collection $categories;
    public Collection $services;

    public ?int $category_id = null;
    public ?int $service_id = null;
    public string $link = '';
    public ?int $quantity = null;

    public ?Service $selectedService = null;
    public float $charge = 0.00;
    public float $userBalance = 0.00;

    public string $metaTitle = 'Create Order';

    protected function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'service_id' => 'required|exists:services,id',
            'link' => 'required|url',
            'quantity' => [
                'required',
                'integer',
                'min:' . ($this->selectedService->min ?? 1),
                'max:' . ($this->selectedService->max ?? 1000),
            ],
        ];
    }

    public function updatedCategoryId($value)
    {
        // Reset dependent properties
        $this->reset('service_id', 'selectedService', 'quantity', 'charge', 'link');

        if ($value) {
            $this->services = Service::where('category_id', $value)->active()->get();
        } else {
            $this->services = collect();
        }
    }

    public function updatedServiceId($value)
    {
        $this->reset('quantity', 'charge');
        if ($value) {
            $this->selectedService = Service::find($value);
            if ($this->selectedService) {
                $this->quantity = $this->selectedService->min;
                $this->calculateCharge();
            }
        } else {
            $this->selectedService = null;
        }
    }

    public function updatedQuantity()
    {
        $this->calculateCharge();
    }

    /**
     * Place Order
     */
    public function placeOrder()
    {
        $this->validate();

        $this->calculateCharge();

        $user = Auth::user();

        if ($user->balance < $this->charge) {
            $this->errorAlert('You do not have enough funds to place this order.');
            return;
        }

        try {
            DB::beginTransaction();

            // Create the order
            // $order = Order::create([
            //     'user_id' => $user->id,
            //     'service_id' => $this->service_id,
            //     'link' => $this->link,
            //     'quantity' => $this->quantity,
            //     'charge' => $this->charge,
            //     'status' => 'pending', // or whatever default status you use
            //     // Add other required fields based on your Order model
            // ]);

            // Deduct balance from user
            $user->decrement('balance', $this->charge);

            // You might also want to create a transaction record
            // Transaction::create([...]);

            DB::commit();

            $this->successAlert('Order placed successfully!');
            $this->redirect(route('user.orders.history'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorAlert('Failed to place order. Please try again.');
            // Log the error for debugging
            \Log::error('Order creation failed: ' . $e->getMessage());
        }
    }

    private function calculateCharge()
    {
        if ($this->quantity && $this->selectedService) {
            $pricePerThousand = $this->selectedService->price;
            $this->charge = ($this->quantity / 1000) * $pricePerThousand;
        } else {
            $this->charge = 0.00;
        }
    }

    public function mount()
    {
        $this->categories = Category::where('is_active', true)->has('activeServices')->orderBy('name')->get();
        $this->services = collect();
        $this->userBalance = Auth::user()->balance ?? 0.00;

        $service_id = request()->get('service_id');
        if ($service_id) {
            $service = Service::find($service_id);
            if ($service) {
                $this->category_id = $service->category_id;
                $this->updatedCategoryId($service->category_id);
                $this->updatedServiceId($service_id);
                $this->service_id = (int) $service_id;

                $this->successAlert('Service selected successfully!');
            }
        }
    }

    public function render()
    {
        return view('livewire.orders.create');
    }
}
