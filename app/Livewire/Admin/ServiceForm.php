<?php

namespace App\Livewire\Admin;

use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\Service;
use App\Services\ApiProviderService;
use App\Traits\LivewireToast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.main')]
class ServiceForm extends Component
{
    use LivewireToast;

    public ?Service $editing = null;

    public bool $isSaving = false;

    public bool $hasChanges = false;

    public array $providerServices = [];

    public bool $isLoadingServices = false;

    public string $metaTitle = '';

    // Form fields
    public string $name = '';

    public ?int $category_id = null;

    public ?int $api_provider_id = null;

    public ?int $api_service_id = null;

    public string $type = 'default';

    public float $price = 0.00;

    public float $api_price = 0.00;

    public float $original_price = 0.00;

    public int $min = 1;

    public int $max = 10000;

    public ?string $description = null;

    public bool $dripfeed = false;

    public bool $cancel = false;

    public bool $refill = false;

    public bool $status = true;

    public string $orderType = 'manual';

    protected $listeners = ['updated' => 'trackChanges'];

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'api_provider_id' => 'nullable|required_if:orderType,api|exists:api_providers,id',
            'api_service_id' => 'nullable|required_if:orderType,api|integer',
            'type' => 'required|string|in:default,subscriptions,custom_comments,custom_comments_package,mentions_with_hashtags,mentions_custom_list,mentions_hashtag,mentions_user_followers,mentions_media_likers,package,comment_likes,comment_replies',
            'price' => 'required|numeric|min:0',
            'api_price' => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'min' => 'required|integer|min:1',
            'max' => 'required|integer|gte:min',
            'description' => 'nullable|string|max:1000',
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
        'api_service_id' => 'API Service',
        'price' => 'Price',
        'api_price' => 'API Price',
        'original_price' => 'Original Price',
        'min' => 'Minimum Amount',
        'max' => 'Maximum Amount',
        'description' => 'Description',
    ];

    #[Computed]
    public function categories(): Collection
    {
        return Category::pluck('name', 'id');
    }

    #[Computed]
    public function apiProviders(): Collection
    {
        return ApiProvider::get(['id', 'name', 'rate']);
    }

    #[Computed]
    public function currentProvider(): ?ApiProvider
    {
        return $this->api_provider_id ? ApiProvider::find($this->api_provider_id) : null;
    }

    public function mount($id = null): void
    {
        if ($id) {
            $this->editing = Service::findOrFail($id);
            $this->metaTitle = 'Edit Service';
            $this->loadServiceData();
        } else {
            $this->metaTitle = 'Add Service';
            $this->resetForm();
        }
    }

    public function trackChanges(): void
    {
        $this->hasChanges = true;
    }

    public function setOrderType(string $type): void
    {
        $this->orderType = $type;

        if ($type === 'manual') {
            $this->resetApiFields();
        }

        $this->trackChanges();
    }

    public function updatedApiProviderId($providerId): void
    {
        $this->resetApiServiceFields();

        if ($providerId) {
            $this->fetchProviderServices($providerId);
        }

        $this->trackChanges();
    }

    public function updatedApiServiceId($serviceId): void
    {
        if (! $serviceId) {
            $this->resetServiceFields();

            return;
        }

        $this->populateServiceFromApi($serviceId);
        $this->trackChanges();
    }

    public function updatedPrice($value): void
    {
        if ($this->orderType === 'manual') {
            $this->recalculateManualPrices($value);
        }
        $this->trackChanges();
    }

    public function fetchProviderServices($providerId): void
    {
        $this->isLoadingServices = true;

        try {
            $provider = ApiProvider::findOrFail($providerId);
            $cacheKey = "provider_services_{$providerId}";

            $services = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($provider) {
                $apiService = new ApiProviderService;

                return $apiService->getServices($provider);
            });

            if ($this->isValidServicesResponse($services)) {
                $this->providerServices = $services;
                $this->infoAlert('Provider services loaded successfully.');
            } else {
                $this->handleServicesError($services, $cacheKey);
            }
        } catch (\Exception $e) {
            $this->handleFetchError($e, $providerId);
        } finally {
            $this->isLoadingServices = false;
        }
    }

    public function save(): void
    {
        $this->isSaving = true;

        try {
            $validated = $this->validate();
            $validated['manual_order'] = $this->orderType === 'manual';

            if ($this->editing) {
                $this->editing->update($validated);
                $this->successAlert('Service updated successfully.');
            } else {
                Service::create($validated);
                $this->successAlert('Service created successfully.');
            }

            $this->hasChanges = false;
            $this->redirect(route('admin.services'), navigate: true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorAlert('Please fix the validation errors.');
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert('An error occurred while saving the service.');
            throw $e;
        } finally {
            $this->isSaving = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.service-form');
    }

    // Private helper methods

    private function loadServiceData(): void
    {
        $this->fill($this->editing->toArray());
        $this->orderType = $this->editing->api_provider_id ? 'api' : 'manual';

        // If it's an API service, fetch the provider services
        if ($this->orderType === 'api' && $this->api_provider_id) {
            $this->fetchProviderServices($this->api_provider_id);
        }
    }

    private function resetForm(): void
    {
        $this->reset([
            'name',
            'category_id',
            'api_provider_id',
            'api_service_id',
            'type',
            'price',
            'api_price',
            'original_price',
            'min',
            'max',
            'description',
            'dripfeed',
            'cancel',
            'refill',
        ]);

        $this->status = true;
        $this->type = 'default';
        $this->min = 1;
        $this->max = 10000;
        $this->price = 0.00;
        $this->api_price = 0.00;
        $this->original_price = 0.00;
        $this->providerServices = [];
        $this->hasChanges = false;
    }

    private function resetApiFields(): void
    {
        $this->reset(['api_provider_id', 'api_service_id']);
        $this->providerServices = [];
    }

    private function resetApiServiceFields(): void
    {
        $this->reset(['api_service_id']);
        $this->providerServices = [];
    }

    private function resetServiceFields(): void
    {
        $this->reset(['name', 'min', 'max', 'api_price', 'original_price', 'price', 'type']);
        $this->type = 'default';
    }

    private function populateServiceFromApi($serviceId): void
    {
        $selectedService = collect($this->providerServices)->firstWhere('service', $serviceId);
        \Log::info($selectedService);
        if (! $selectedService) {
            $this->errorAlert('Selected service not found.');

            return;
        }

        $this->name = $selectedService['name'];
        $this->min = $selectedService['min'];
        $this->max = $selectedService['max'];
        $this->type = $this->formatServiceType($selectedService['type']) ?? 'default';
        $this->dripfeed = $selectedService['dripfeed'] ?? false;
        $this->cancel = $selectedService['cancel'] ?? false;
        $this->refill = $selectedService['refill'] ?? false;

        $this->calculatePricesFromApi($selectedService);
    }

    private function formatServiceType(string $type): string
    {
        return strtolower(str_replace(' ', '_', $type));
    }

    private function calculatePricesFromApi(array $service): void
    {
        $rate = $this->currentProvider->rate ?? 1;
        $apiPrice = (float) $service['rate'];

        $this->api_price = $apiPrice;
        $this->original_price = $apiPrice * $rate;
        $this->price = $this->original_price * 1.20; // 20% markup
    }

    private function recalculateManualPrices($price): void
    {
        $this->api_price = $price;
        $rate = $this->currentProvider->rate ?? 1;
        $this->original_price = $price * $rate;
    }

    private function isValidServicesResponse($services): bool
    {
        return ! empty($services) && is_array($services) && ! isset($services['error']);
    }

    private function handleServicesError($services, string $cacheKey): void
    {
        $errorMessage = is_array($services) && isset($services['error'])
            ? $services['error']
            : 'Could not fetch services from provider.';

        $this->errorAlert($errorMessage);
        $this->providerServices = [];
        Cache::forget($cacheKey);
    }

    private function handleFetchError(\Exception $e, $providerId): void
    {
        $this->errorAlert('Failed to connect to the provider: '.$e->getMessage());
        $this->providerServices = [];
        Cache::forget("provider_services_{$providerId}");
    }
}
