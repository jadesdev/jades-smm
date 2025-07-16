<?php

namespace App\Livewire\Admin;

use App\Models\ApiProvider;
use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('admin.layouts.main')]
class ApiProviderManager extends Component
{
    use LivewireToast;
    use WithPagination;

    public string $metaTitle = "API Providers";

    // Form Properties
    public ?ApiProvider $editing = null;
    public ?ApiProvider $deleting = null;
    public ?ApiProvider $currencyEditing = null;
    public ?ApiProvider $syncProvider = null;
    public ?ApiProvider $importProvider = null;

    public string $name = '';
    public string $url = '';
    public string $api_key = '';
    public float $rate = 0.0;
    public float $currencyRate = 0.0;
    public string $syncRequestType = '0';
    public int $syncPercentage = 100;
    public array $syncOptions = ['original_price'];
    public int $importPercentage = 100;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:api_providers,name,' . $this->editing?->id,
            'url' => 'required|url|max:255',
            'api_key' => [
                $this->editing ? 'nullable' : 'required',
                'string',
                'min:8'
            ],
            'rate' => $this->editing ? 'nullable|numeric' : 'required|numeric',
            'currencyRate' => $this->currencyEditing ? 'required|numeric|min:0.01' : 'nullable|numeric',
        ];
    }
    protected $validationAttributes = [
        'name' => 'Provider Name',
        'url' => 'API URL',
        'api_key' => 'API Key',
        'rate' => 'Rate',
        'currencyRate' => 'Currency Rate',
        'syncRequestType' => 'request type',
        'syncPercentage' => 'percentage increase',
        'syncOptions' => 'sync options',
    ];

    public function add(): void
    {
        $this->resetErrorBag();
        $this->editing = null;
        $this->reset('name', 'url', 'api_key', 'rate');
        $this->dispatch('open-modal', name: 'provider-modal');
    }

    public function edit(ApiProvider $provider): void
    {
        $this->resetErrorBag();
        $this->editing = $provider;

        $this->name = $provider->name;
        $this->url = $provider->url;
        $this->rate = $provider->rate;

        $this->api_key = '';

        $this->dispatch('open-modal', name: 'provider-modal');
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
        } catch (\Exception $e) {
            $this->errorAlert($e->getMessage());
            return;
        }
        $data = [
            'name' => $validated['name'],
            'url' => $validated['url'],
            'rate' => $validated['rate'],
        ];

        if (!empty($validated['api_key'])) {
            $data['api_key'] = $validated['api_key'];
        }

        if ($this->editing) {
            $this->editing->update($data);
            $this->successAlert('API Provider updated successfully.');
        } else {
            ApiProvider::create($data);
            $this->successAlert('API Provider created successfully.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->dispatch('close-modal', name: 'provider-modal');
        $this->reset('name', 'url', 'api_key', 'editing', 'rate');
        $this->currencyEditing = null;
    }

    public function delete(ApiProvider $provider): void
    {
        $this->deleting = $provider;
        $this->dispatch('open-modal', name: 'delete-provider-modal');
    }

    public function confirmDelete(): void
    {
        if ($this->deleting) {
            $this->deleting->delete();
            $this->successAlert('API Provider deleted successfully.');
        }
        $this->closeDeleteModal();
    }

    public function closeDeleteModal(): void
    {
        $this->dispatch('close-modal', name: 'delete-provider-modal');
        $this->deleting = null;
    }
    /**
     * Toggles status of a provider.
     */
    public function toggleStatus(ApiProvider $provider): void
    {
        $provider->update(['is_active' => !$provider->is_active]);
        $status = $provider->is_active ? 'activated' : 'deactivated';
        $this->successAlert("Provider has been {$status}.");
    }

    /**
     * Set provider currency.
     */
    public function setCurrency(ApiProvider $provider): void
    {
        $this->resetErrorBag();
        $this->currencyEditing = $provider;
        $this->currencyRate = $provider->rate;
        $this->dispatch('open-modal', name: 'currency-modal');
    }
    public function saveCurrency(): void
    {
        $this->validate(['currencyRate' => 'required|numeric|min:0.001']);

        if ($this->currencyEditing) {
            $this->currencyEditing->update(['rate' => $this->currencyRate]);
            $this->successAlert('Currency rate updated successfully.');
        }

        $this->closeCurrencyModal();
    }

    public function closeCurrencyModal(): void
    {
        $this->dispatch('close-modal', name: 'currency-modal');
        $this->currencyEditing = null;
        $this->currencyRate = 0.0;
    }

    /**
     * Update service price.
     */
    public function updateServices(ApiProvider $provider): void
    {
        $this->resetErrorBag();
        $this->syncRequestType = '0';
        $this->syncPercentage = 100;
        $this->syncOptions = ['original_price'];
        $this->syncProvider = $provider;
        $this->dispatch('open-modal', name: 'update-services-modal');
    }

    public function syncProviderServices(): void
    {
        if (!$this->syncProvider) {
            $this->errorAlert('No provider selected.');
            return;
        }
        $validated = $this->validate([
            'syncRequestType' => 'required|in:0,1',
            'syncPercentage' => 'required|numeric|min:0',
            'syncOptions' => 'required|array|min:1',
            'syncOptions.*' => 'string',
        ]);
        \Log::info($validated);
        $this->closeUpdateServicesModal();
        $this->successAlert('Services synced successfully.');
    }

    public function closeUpdateServicesModal(): void
    {
        $this->dispatch('close-modal', name: 'update-services-modal');
        $this->syncProvider = null;
    }

    public function importServicesModal(ApiProvider $provider): void
    {
        $this->importProvider = $provider;
        $this->importPercentage = 100;
        $this->dispatch('open-modal', name: 'import-services-modal');
    }

    public function importServices(): void
    {
        if (!$this->importProvider) {
            $this->errorAlert('No provider selected.');
            return;
        }
        $validated = $this->validate([
            'importPercentage' => 'required|numeric|min:0',
        ]);
        \Log::info($validated);
        $this->closeImportModal();
        $this->successAlert('Services imported successfully.');
    }

    public function closeImportModal(): void
    {
        $this->dispatch('close-modal', name: 'import-services-modal');
        $this->importProvider = null;
    }
    /**
     * Update provider balance.
     */
    public function updateProviderBalance(ApiProvider $provider): void
    {
        $this->resetErrorBag();
        $provider->balance = rand(100, 1000);
        $provider->save();
        $this->successAlert('Provider balance updated successfully.');
    }

    public function render()
    {
        $providers = ApiProvider::latest()->paginate(15);
        return view('livewire.admin.apiprovider-manager', compact('providers'));
    }
}
