<?php

namespace App\Livewire;


use App\Models\Category;
use App\Traits\LivewireToast;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('front.layouts.main')]
class Services extends Component
{
    use LivewireToast;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'category')]
    public ?int $selectedCategory = null;

    public $categories;

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
        $this->reset('search', 'selectedCategory');
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $cacheKey = 'categories_actives_' . md5($this->search . '_' . $this->selectedCategory);
        $this->categories = Cache::remember($cacheKey, now()->addHours(2), function () {
            return Category::where('is_active', true)
                ->whereHas('services', fn($q) => $q->active())
                ->with([
                    'services' => fn($q) => $q->active()
                        ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                ])
                ->when($this->selectedCategory, fn($q) => $q->where('id', $this->selectedCategory))
                ->orderBy('name')
                ->get();
        });
    }

    public function mount()
    {
        $this->metaTitle = 'Services';
        $this->metaDescription = 'Browse our wide range of social media marketing services for Instagram, YouTube, TikTok and more.';
        $this->metaKeywords = 'SMM services, Instagram followers, YouTube views, TikTok likes, social media marketing';

        $this->loadCategories();
    }

    public function render()
    {
        $categoriesQuery = Category::query()
            ->where('is_active', true)
            ->with([
                'services' => function ($query) {
                    $query->active()
                        ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
                },
            ])
            ->whereHas('services', function ($query) {
                $query->active()
                    ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
            });

        if ($this->selectedCategory) {
            $categoriesQuery->where('id', $this->selectedCategory);
        }

        return view('livewire.services', [
            'filteredCategories' => $categoriesQuery->orderBy('name')->get(),
        ]);
    }
}
