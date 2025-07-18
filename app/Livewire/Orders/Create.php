<?php

namespace App\Livewire\Orders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Traits\LivewireToast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
            // Pre-fill quantity with the minimum amount
            $this->quantity = $this->selectedService->min;
            $this->calculateCharge();
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


        $this->successAlert('Order placed successfully!');
        $this->redirect(route('user.orders.history'), navigate: true);
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
    }

    public function render()
    {
        return view('livewire.orders.create');
    }
}
