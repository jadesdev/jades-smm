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

    public ?Service $deleting = null;
    
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
    public string $view = 'list';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'providerFilter' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

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
