<?php

namespace App\Livewire\Admin;

use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\Service;
use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

#[Layout('admin.layouts.main')]
class ServiceManager extends Component
{
    use LivewireToast;
    use WithPagination;

    public string $metaTitle = "Services";

    // Form Properties
    public ?Service $editing = null;
    public ?Service $deleting = null;

    // Form fields
    public string $name = '';
    public ?int $category_id = null;
    public ?int $api_provider_id = null;
    public ?int $api_service_id = null;
    public string $type = 'default';
    public float $price = 0.00;
    public int $min = 1;
    public int $max = 1000;
    public ?string $description = null;
    public bool $dripfeed = false;
    public bool $cancel = false;
    public bool $refill = false;
    public bool $status = true;

    // Search and filtering
    public string $search = '';
    public string $statusFilter = '';
    public string $categoryFilter = '';
    public string $providerFilter = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public array $selectedServices = [];
    public bool $selectAll = false;
    public bool $bulkDeleteConfirmation = false;


    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'providerFilter' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'api_provider_id' => 'nullable|exists:api_providers,id',
            'api_service_id' => 'nullable|integer',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'min' => 'required|integer|min:1',
            'max' => 'required|integer|gte:min',
            'description' => 'nullable|string',
            'dripfeed' => 'boolean',
            'cancel' => 'boolean',
            'refill' => 'boolean',
            'status' => 'boolean',
        ];
    }

    protected $validationAttributes = [
        'name' => 'Service Name',
        'category_id' => 'Category',
        'api_provider_id' => 'API Provider',
        'api_service_id' => 'API Service ID',
        'price' => 'Price',
    ];

    public function add(): void
    {
        $this->resetErrorBag();
        $this->editing = null;
        $this->reset(
            'name',
            'category_id',
            'api_provider_id',
            'api_service_id',
            'type',
            'price',
            'min',
            'max',
            'description',
            'dripfeed',
            'cancel',
            'refill',
            'status'
        );
        $this->status = true; // Default to active
        $this->type = 'default';
        $this->min = 1;
        $this->max = 1000;
        $this->price = 0.00;
        $this->dispatch('open-modal', name: 'service-modal');
    }

    public function edit(Service $service): void
    {
        $this->resetErrorBag();
        $this->editing = $service;
        $this->fill($service->toArray());
        $this->dispatch('open-modal', name: 'service-modal');
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['manual_order'] = $this->api_provider_id ? 0 : 1;

        if ($this->editing) {
            $this->editing->update($validated);
            $this->successAlert('Service updated successfully.');
        } else {
            Service::create($validated);
            $this->successAlert('Service created successfully.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->dispatch('close-modal', name: 'service-modal');
        $this->editing = null;
    }

    public function delete(Service $service): void
    {
        $this->deleting = $service;
        $this->bulkDeleteConfirmation = false;
        $this->dispatch('open-modal', name: 'delete-service-modal');
    }

    public function confirmBulkDelete(): void
    {
        if (count($this->selectedServices) > 0) {
            $this->bulkDeleteConfirmation = true;
            $this->dispatch('open-modal', name: 'delete-service-modal');
        } else {
            $this->infoAlert('You must select at least one service to delete.');
        }
    }

    public function confirmDelete(): void
    {
        if ($this->bulkDeleteConfirmation) {
            $count = count($this->selectedServices);
            Service::whereIn('id', $this->selectedServices)->delete();
            $this->successAlert("Successfully deleted {$count} services.");
            $this->reset('selectedServices', 'selectAll', 'bulkDeleteConfirmation');
        } elseif ($this->deleting) {
            $this->deleting->delete();
            $this->successAlert('Service deleted successfully.');
        }
        $this->closeDeleteModal();
    }


    public function closeDeleteModal(): void
    {
        $this->dispatch('close-modal', name: 'delete-service-modal');
        $this->deleting = null;
        $this->bulkDeleteConfirmation = false;
    }

    public function enableSelected(): void
    {
        $count = count($this->selectedServices);
        if ($count > 0) {
            Service::whereIn('id', $this->selectedServices)->update(['status' => true]);
            $this->successAlert("Enabled {$count} services.");
            $this->reset('selectedServices', 'selectAll');
        } else {
            $this->infoAlert('You must select at least one service.');
        }
    }

    public function disableSelected(): void
    {
        $count = count($this->selectedServices);
        if ($count > 0) {
            Service::whereIn('id', $this->selectedServices)->update(['status' => false]);
            $this->successAlert("Disabled {$count} services.");
            $this->reset('selectedServices', 'selectAll');
        } else {
            $this->infoAlert('You must select at least one service.');
        }
    }

    public function toggleStatus(Service $service): void
    {
        $service->update(['status' => !$service->status]);
        $status = $service->status ? 'activated' : 'deactivated';
        $this->successAlert("Service has been {$status}.");
    }

    public function sortByColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updating($key)
    {
        if (in_array($key, ['search', 'statusFilter', 'categoryFilter', 'providerFilter'])) {
            $this->resetPage();
            $this->reset('selectedServices', 'selectAll');
        }
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedServices = $this->servicesOnPage->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedServices = [];
        }
    }
    public function updatedSelectedServices(): void
    {
        $pageIds = $this->servicesOnPage->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $allOnPageSelected = !array_diff($pageIds, $this->selectedServices);
        $this->selectAll = (count($pageIds) > 0) && $allOnPageSelected;
    }

    private function getServices()
    {
        return Service::with(['category', 'apiProvider'])
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%')
                ->orWhere('id', 'like', '%' . $this->search . '%')
                ->orWhere('api_service_id', 'like', '%' . $this->search . '%'))
            ->when($this->statusFilter === 'active', fn($query) => $query->where('status', true))
            ->when($this->statusFilter === 'inactive', fn($query) => $query->where('status', false))
            ->when($this->categoryFilter, fn($query) => $query->where('category_id', $this->categoryFilter))
            ->when($this->providerFilter, function ($query) {
                if ($this->providerFilter === 'manual') {
                    return $query->whereNull('api_provider_id');
                }
                return $query->where('api_provider_id', $this->providerFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection);
    }

    #[Computed]
    public function servicesOnPage()
    {
        return $this->getServices()->paginate(50);
    }

    public function render()
    {
        $categories = Category::pluck('name', 'id');
        $apiProviders = ApiProvider::pluck('name', 'id');

        return view('livewire.admin.service-manager', [
            'services' => $this->servicesOnPage,
            'categories' => $categories,
            'apiProviders' => $apiProviders
        ]);
    }
}
