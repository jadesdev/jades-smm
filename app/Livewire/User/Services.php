<?php

namespace App\Livewire\User;

use App\Models\Category;
use App\Traits\LivewireToast;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Services extends Component
{
    use LivewireToast;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'category')]
    public ?int $selectedCategory = null;
    public $categories;
    public $filteredCategories = [];

    // meta
    public string $metaTitle;

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    #[Computed(persist: true)]
    public function categoriesForFilter()
    {
        return Category::where('is_active', true)
            ->whereHas('services', fn($q) => $q->active())
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategory = null;
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Cache::remember('categories_actives', now()->addHours(2), function () {
            return Category::where('is_active', true)
                ->whereHas('services', fn($q) => $q->active())
                ->with(['services' => fn($q) => $q->active()])
                ->orderBy('name')
                ->get();
        });
        $this->loadFilteredCategories();
    }
    public function loadFilteredCategories()
    {

        $categoriesQuery = Category::where('is_active', true)
            ->with(['services' => function ($query) {
                $query->active()
                    ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
            }])
            ->whereHas('services', function ($query) {
                $query->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
            });

        if ($this->selectedCategory) {
            $categoriesQuery->where('id', $this->selectedCategory);
        }

        $this->filteredCategories = $categoriesQuery->orderBy('name')->get()
            ->filter(fn($category) => $category->services->isNotEmpty())
            ->values();

    }
    public function updatedSearch()
    {
        $this->loadFilteredCategories();
    }

    public function updatedSelectedCategory()
    {
        $this->loadFilteredCategories();
    }


    public function mount()
    {
        // Set meta
        $this->metaTitle = 'Services';
        $this->metaDescription = 'Browse our wide range of social media marketing services for Instagram, YouTube, TikTok and more.';
        $this->metaKeywords = 'SMM services, Instagram followers, YouTube views, TikTok likes, social media marketing';

        // Load initial data
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.user.services');
    }
}
