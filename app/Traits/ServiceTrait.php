<?php

namespace App\Traits;

use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

trait ServiceTrait
{
    /**
     * Update the status of services and their corresponding categories for a given API provider.
     */
    public function updateProviderServices(ApiProvider $apiProvider, int $status): void
    {
        if (!$apiProvider->services) {
            return;
        }
        foreach ($apiProvider->services as $service) {
            $service->update(['status' => $status]);

            if ($service->category) {
                $service->category->update(['status' => $status]);
            }
        }
    }

    /**
     * Delete services and their corresponding categories for a given API provider.
     */
    public function deleteProviderServices(int $providerId): void
    {
        $apiProvider = ApiProvider::find($providerId);

        if (!$apiProvider || !$apiProvider->services) {
            return;
        }

        foreach ($apiProvider->services as $service) {
            if ($service->category) {
                $service->category->forceDelete();
            }
            $service->forceDelete();
        }
    }

    /**
     * Get or create a category by name.
     */
    protected function getOrCreateCategory(?string $name): int
    {
        if (!$name) {
            return Category::firstOrCreate(
                ['name' => 'Default Category'],
                ['status' => 1]
            )->id;
        }
        return Category::firstOrCreate(
            ['name' => $name],
            ['status' => 1]
        )->id;
    }

    /**
     * Calculate the new value after a percentage increase.
     */
    public function percentageIncrease(float $percentage, float $number): float
    {
        return $number * (1 + ($percentage / 100));
    }

    /**
     * Create a new provider service with alternative data structure.
     */
    public function createProviderService(array $item, int $providerId, float $percentage, float $rate = 1): Service
    {

        return Service::create([
            'category_id' => $item['category'],
            'api_provider_id' => $providerId,
            'api_service_id'  => $item['service'],
            'name' => $item['name'],
            'type' => $this->getServiceType($item['type']) ?? 'default',
            'min' => $item['min'],
            'max' => $item['max'],
            'price' => $this->calculatePriceWithMargin($item['rate'], $percentage, $rate),
            'api_price'       => $item['rate'],
            'original_price'       => $item['rate'] * $rate,
            'description' => $item['desc'] ?? $item['description'] ?? '',
            'dripfeed' => $item['dripfeed'] ?? 0,
            'cancel' => $item['cancel'] ?? 0,
            'refill' => $item['refill'] ?? 0,
        ]);
    }

    /**
     * Update provider service with alternative data structure.
     */
    public function updateProviderService(Service $service, array $item, int $providerId, float $percentage, float $rate = 1): Service
    {
        $service->update([
            'api_provider_id' => $providerId,
            'category_id' => $item['category'],
            'name' => $item['name'],
            'min' => $item['min'],
            'max' => $item['max'],
            'api_price' => $item['rate'] ?? $item['api_price'],
            'original_price' => ($item['rate'] * $rate) ?? $item['original_price'],
            'api_service_id' => $item['service'] ?? $item['api_service_id'],
            'type' => $this->getServiceType($item['type']) ?? 'default',
            'description' => $item['desc'] ?? $item['description'] ?? '',
            'dripfeed' => $item['dripfeed'] ?? 0,
            'cancel' => $item['cancel'] ?? 0,
            'refill' => $item['refill'] ?? 0,
            'price' => $this->calculatePriceWithMargin($item['rate'], $percentage, $rate),
        ]);

        return $service;
    }

    /**
     * Sort an associative array of services by a new key.
     */
    public function sortServicesByKey(array $array, string $newKey): array
    {
        if (empty($array)) {
            return [];
        }

        $newKeys = array_column($array, $newKey);

        if (empty($newKeys)) {
            return $array;
        }

        return array_combine(array_values($newKeys), array_values($array));
    }

    /**
     * Synchronize existing services with new data.
     */
    public function syncExistingServices(array $dataServices, array $params): bool
    {
        if (empty($dataServices)) {
            return false;
        }

        $syncOptions = $params['sync_options'];

        foreach ($dataServices as $item) {
            $service = Service::where('api_service_id', $item['service'])
                ->where('api_provider_id', $params['api_provider_id'])
                ->first();

            if (!$service) {
                continue;
            }

            $updateData = ['type' => $this->formatServiceType($item['type'])];
            if (!empty($syncOptions['new_price'])) {
                $updateData['price'] = $this->calculatePriceWithMargin($item['rate'], $params['percentage'], $params['rate']);
            }

            if (!empty($syncOptions['service_desc'])) {
                $updateData['description'] = $item['desc'] ?? $item['description'] ?? '';
            }

            if (!empty($syncOptions['service_name'])) {
                $updateData['name'] = $item['name'];
            }

            if (!empty($syncOptions['original_price'])) {
                $updateData['api_price'] = (float) $item['rate'];
                $updateData['original_price'] = $item['rate'] * $params['rate'];
            }

            if (!empty($syncOptions['min_max_dripfeed'])) {
                $updateData['min'] = $item['min'];
                $updateData['max'] = $item['max'];
                $updateData['dripfeed'] = $item['dripfeed'] ?? 0;
            }

            if (!empty($syncOptions['old_service_status'])) {
                $updateData['status'] = 1;
            }

            $service->update($updateData);
        }

        return true;
    }

    /**
     * Synchronize new services with new data.
     */
    public function syncNewServices(array $services, array $params): bool
    {
        if (empty($services)) {
            return false;
        }

        foreach ($services as $item) {
            $categoryId = $this->getOrCreateCategory($item['category']);
            $item['category'] = $categoryId;

            $existingService = Service::where('api_service_id', $item['service'])
                ->where('api_provider_id', $params['api_provider_id'])
                ->first();

            if (!$existingService) {
                $this->createProviderService($item, $params['api_provider_id'], $params['percentage'], $params['rate']);
            }
        }

        return true;
    }

    /**
     * Format the service type to lowercase and replace spaces with underscores.
     */
    public function formatServiceType(string $type): string
    {
        return strtolower(str_replace(' ', '_', $type));
    }

    /**
     * Get formatted service type.
     */
    public function getServiceType(string $type): string
    {
        return strtolower(preg_replace('/[\s\-]+/', '_', $type));
    }

    /**
     * Calculate service price based on API price and provider rate.
     */
    public function getServicePrice(float $price, float $rate): float
    {
        return $price * $rate;
    }
    protected function calculatePriceWithMargin(float $apiPrice, float $percentage, float $rate): float
    {
        $price = $apiPrice + ($percentage / 100) * $apiPrice;
        return round($price * $rate, 4);
    }
}
