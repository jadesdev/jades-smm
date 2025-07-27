<?php

namespace App\Services;

use App\Models\ApiProvider;
use App\Models\Service;
use App\Traits\ServiceTrait;
use Exception;
use Http;
use Log;

class ApiProviderService
{
    use ServiceTrait;

    public function syncProviderServices(ApiProvider $provider, array $params)
    {
        $services = $this->getServices($provider);
        $services = $this->sortServicesByKey($services, 'service');
        $currentServices = $this->sortServicesByKey(json_decode($provider->services, true), 'api_service_id');
        $disabledServices = array_diff_key($currentServices, $services);
        $newServices = array_diff_key($services, $currentServices);
        $existsServices = array_diff_key($services, $newServices);
        // disable api services the no longer exist ($disabled services)
        if (! empty($disabledServices)) {
            $service = Service::whereIn('api_service_id', $disabledServices);
            $service->update([
                'status' => 2,
            ]);
        }

        // create new api services ($new services)
        if ($params['syncRequestType'] === 'new' && ! empty($newServices)) {
            $this->syncNewServices($newServices, [
                'api_provider_id' => $provider->id,
                'percentage' => $params['syncPercentage'] ?? 100,
                'rate' => $provider->rate,
            ]);
        } elseif ($params['syncRequestType'] === 'current' && ! empty($existsServices)) {
            $this->syncExistingServices($existsServices, [
                'api_provider_id' => $provider->id,
                'percentage' => $params['syncPercentage'] ?? 100,
                'rate' => $provider->rate,
                'sync_options' => $params['syncOptions'],
            ]);
        } elseif ($params['syncRequestType'] === 'all' && ! empty($services)) {
            $this->syncExistingServices($existsServices, [
                'api_provider_id' => $provider->id,
                'percentage' => $params['syncPercentage'] ?? 100,
                'rate' => $provider->rate,
                'sync_options' => $params['syncOptions'],
            ]);
            $this->syncNewServices($services, [
                'api_provider_id' => $provider->id,
                'percentage' => $params['syncPercentage'] ?? 100,
                'rate' => $provider->rate,
            ]);
        }

        return true;
    }

    public function getBalance(ApiProvider $provider)
    {
        $payload = [
            'key' => $provider->api_key,
            'action' => 'balance',
        ];

        return $this->postRequest($provider->url, $payload);
    }

    public function getServices(ApiProvider $provider)
    {
        $payload = [
            'key' => $provider->api_key,
            'action' => 'services',
        ];

        return $this->postRequest($provider->url, $payload);
    }

    public function postRequest($endpoint, $payload)
    {
        try {
            $res = Http::timeout(240)->post($endpoint, $payload);

            return $res->json();
        } catch (Exception $e) {
            Log::error('Provider request exception:'.$e->getMessage());
            throw $e;
        }
    }

    public function getRequest($endpoint, $payload = null)
    {
        return Http::get($endpoint, $payload)->json();
    }
}
