<?php

namespace App\Livewire\Admin;

use App\Traits\LivewireToast;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('admin.layouts.main')]
class CategoryManager extends Component
{
    use LivewireToast;
    use WithPagination;

    // Meta
    public string $metaTitle = "Categories ";
    public string $metaDescription;
    public string $metaKeywords;
    public string $metaImage;

    // Existing properties
    public ?Category $editing = null;
    public string $name = '';
    public $isActive = 1;
    public ?Category $deleting = null;

    // New properties for enhanced functionality
    public string $search = '';
    public string $statusFilter = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    
    // Bulk operations
    public array $selectedCategories = [];
    public bool $selectAll = false;
    public string $bulkAction = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'isActive' => 'boolean',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedCategories = $this->getCategories()->pluck('id')->toArray();
        } else {
            $this->selectedCategories = [];
        }
    }

    public function updatedSelectedCategories(): void
    {
        $this->selectAll = count($this->selectedCategories) === $this->getCategories()->count();
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function executeBulkAction(): void
    {
        if (empty($this->selectedCategories) || empty($this->bulkAction)) {
            $this->errorAlert('Please select categories and an action.');
            return;
        }

        $selectedCount = count($this->selectedCategories);

        switch ($this->bulkAction) {
            case 'activate':
                Category::whereIn('id', $this->selectedCategories)->update(['is_active' => true]);
                $this->successAlert("$selectedCount categories activated successfully.");
                break;

            case 'deactivate':
                Category::whereIn('id', $this->selectedCategories)->update(['is_active' => false]);
                $this->successAlert("$selectedCount categories deactivated successfully.");
                break;

            case 'delete':
                $this->dispatch('open-modal', name: 'bulk-delete-modal');
                return;
        }

        $this->resetBulkSelection();
    }

    public function confirmBulkDelete(): void
    {
        if (!empty($this->selectedCategories)) {
            $selectedCount = count($this->selectedCategories);
            Category::whereIn('id', $this->selectedCategories)->delete();
            $this->successAlert("$selectedCount categories deleted successfully.");
            $this->resetBulkSelection();
        }
        $this->dispatch('close-modal', name: 'bulk-delete-modal');
    }

    public function closeBulkDeleteModal(): void
    {
        $this->dispatch('close-modal', name: 'bulk-delete-modal');
    }

    private function resetBulkSelection(): void
    {
        $this->selectedCategories = [];
        $this->selectAll = false;
        $this->bulkAction = '';
    }

    public function add(): void
    {
        $this->editing = null;
        $this->name = '';
        $this->isActive = true;
        $this->dispatch('open-modal', name: 'category-modal');
    }

    public function edit(Category $category): void
    {
        $this->editing = $category;
        $this->name = $category->name;
        $this->isActive = $category->is_active;
        $this->dispatch('open-modal', name: 'category-modal');
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editing) {
            $this->editing->update([
                'name' => $this->name,
                'is_active' => $this->isActive,
            ]);
            $this->successAlert('Category updated successfully.');
        } else {
            Category::create([
                'name' => $this->name,
                'is_active' => $this->isActive,
            ]);
            $this->successAlert('Category created successfully.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->dispatch('close-modal', name: 'category-modal');
        $this->reset('name', 'isActive', 'editing');
    }

    public function delete(Category $category): void
    {
        $this->deleting = $category;
        $this->dispatch('open-modal', name: 'delete-category-modal');
    }

    public function confirmDelete(): void
    {
        if ($this->deleting) {
            $this->deleting->delete();
            $this->successAlert('Category deleted successfully.');
        }
        $this->closeDeleteModal();
    }

    public function closeDeleteModal(): void
    {
        $this->dispatch('close-modal', name: 'delete-category-modal');
        $this->deleting = null;
    }

    private function getCategories()
    {
        return Category::query()
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->statusFilter === 'active', fn($query) => $query->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn($query) => $query->where('is_active', false))
            ->orderBy($this->sortBy, $this->sortDirection);
    }

    public function render()
    {
        $categories = $this->getCategories()->paginate(30);
        
        return view('livewire.admin.category-manager', compact('categories'));
    }
}