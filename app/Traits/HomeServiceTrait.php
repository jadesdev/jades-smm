<?php

namespace App\Traits;

use App\Models\Service;
use Cache;

trait HomeServiceTrait
{

    private function getHomePlatforms()
    {
        return ['instagram', 'facebook', 'youtube', 'tiktok', 'telegram'];
    }


    private function getHomeServices()
    {
        return Cache::remember('home_services', 3600, function () {
            $platforms = $this->getHomePlatforms();
            $services = [];

            foreach ($platforms as $platform) {
                $platformServices = Cache::remember("home_services_{$platform}", 3600, function () use ($platform) {
                    return Service::where('status', 1)
                        ->where('name', 'LIKE', '%' . $platform . '%')
                        ->orderBy('manual_order', 'asc')
                        ->orderBy('price', 'asc')
                        ->take(4)
                        ->get();
                });

                $services[$platform] = $platformServices->map(function ($service) use ($platform) {
                    return [
                        'id' => $service->id,
                        'title' => $service->name,
                        'price' => $service->price,
                        'unit' => $this->getServiceUnit($service->name),
                        'min' => $service->min,
                        'max' => $service->max,
                        'description' => $service->description,
                        'features' => $this->getServiceFeatures($service),
                        'icon' => $this->getPlatformIcon($platform, $service->name),
                        'gradient' => $this->getPlatformGradient($platform),
                        'bg' => $this->getPlatformBackground($platform),
                        'border' => $this->getPlatformBorder($platform),
                        'darkBg' => $this->getPlatformDarkBackground($platform),
                        'darkBorder' => $this->getPlatformDarkBorder($platform),
                    ];
                })->toArray();
            }

            return $services;
        });
    }

    private function getServiceUnit($serviceName)
    {
        $serviceName = strtolower($serviceName);

        if (str_contains($serviceName, 'follower')) {
            return 'per 1,000 followers';
        } elseif (str_contains($serviceName, 'like')) {
            return 'per 1,000 likes';
        } elseif (str_contains($serviceName, 'view')) {
            return 'per 1,000 views';
        } elseif (str_contains($serviceName, 'subscriber')) {
            return 'per 1,000 subscribers';
        } elseif (str_contains($serviceName, 'comment')) {
            return 'per 100 comments';
        } elseif (str_contains($serviceName, 'share')) {
            return 'per 1,000 shares';
        } elseif (str_contains($serviceName, 'member')) {
            return 'per 1,000 members';
        } else {
            return 'per 1,000 units';
        }
    }

    private function getServiceFeatures($service)
    {
        $features = [];

        if ($service->refill) {
            $features[] = 'Refill guarantee';
        }

        if ($service->cancel) {
            $features[] = 'Cancellable';
        }

        if ($service->dripfeed) {
            $features[] = 'Drip-feed available';
        }

        $serviceName = strtolower($service->name);

        if (str_contains($serviceName, 'instagram')) {
            $features[] = 'High quality accounts';
            if (!in_array('Refill guarantee', $features)) {
                $features[] = 'Fast delivery';
            }
        } elseif (str_contains($serviceName, 'youtube')) {
            $features[] = 'Real engagement';
            $features[] = 'Safe for monetization';
        } elseif (str_contains($serviceName, 'tiktok')) {
            $features[] = 'High retention';
            $features[] = 'Fast delivery';
        } elseif (str_contains($serviceName, 'facebook')) {
            $features[] = 'Real accounts';
            $features[] = 'Instant start';
        } elseif (str_contains($serviceName, 'telegram')) {
            $features[] = 'Active members';
            $features[] = 'Permanent results';
        }

        if (count($features) < 3) {
            $defaultFeatures = ['Quality guaranteed', 'Fast delivery', '24/7 support'];
            foreach ($defaultFeatures as $feature) {
                if (!in_array($feature, $features) && count($features) < 3) {
                    $features[] = $feature;
                }
            }
        }

        return array_slice($features, 0, 3);
    }


    private function getPlatformIcon($platform, $serviceName)
    {
        $serviceName = strtolower($serviceName);

        if (str_contains($serviceName, 'follower') || str_contains($serviceName, 'subscriber') || str_contains($serviceName, 'member')) {
            return 'fas fa-users';
        } elseif (str_contains($serviceName, 'like')) {
            return 'fas fa-heart';
        } elseif (str_contains($serviceName, 'view')) {
            return 'fas fa-eye';
        } elseif (str_contains($serviceName, 'comment')) {
            return 'fas fa-comments';
        } elseif (str_contains($serviceName, 'share')) {
            return 'fas fa-share';
        }

        // Platform default icons
        switch ($platform) {
            case 'instagram':
                return 'fab fa-instagram';
            case 'facebook':
                return 'fab fa-facebook';
            case 'youtube':
                return 'fab fa-youtube';
            case 'tiktok':
                return 'fab fa-tiktok';
            case 'telegram':
                return 'fab fa-telegram';
            default:
                return 'fas fa-star';
        }
    }

    private function getPlatformGradient($platform)
    {
        switch ($platform) {
            case 'instagram':
                return 'from-pink-500 to-purple-600';
            case 'facebook':
                return 'from-blue-500 to-blue-700';
            case 'youtube':
                return 'from-red-500 to-orange-600';
            case 'tiktok':
                return 'from-gray-800 to-gray-900';
            case 'telegram':
                return 'from-blue-400 to-blue-600';
            default:
                return 'from-gray-500 to-gray-700';
        }
    }

    private function getPlatformBackground($platform)
    {
        switch ($platform) {
            case 'instagram':
                return 'from-pink-50 to-purple-50';
            case 'facebook':
                return 'from-blue-50 to-blue-100';
            case 'youtube':
                return 'from-red-50 to-orange-50';
            case 'tiktok':
                return 'from-gray-50 to-gray-100';
            case 'telegram':
                return 'from-blue-50 to-blue-100';
            default:
                return 'from-gray-50 to-gray-100';
        }
    }

    private function getPlatformBorder($platform)
    {
        switch ($platform) {
            case 'instagram':
                return 'border-pink-200';
            case 'facebook':
                return 'border-blue-200';
            case 'youtube':
                return 'border-red-200';
            case 'tiktok':
                return 'border-gray-300';
            case 'telegram':
                return 'border-blue-200';
            default:
                return 'border-gray-300';
        }
    }

    private function getPlatformDarkBackground($platform)
    {
        switch ($platform) {
            case 'instagram':
                return 'dark:from-pink-900/20 dark:to-purple-900/20';
            case 'facebook':
                return 'dark:from-blue-900/20 dark:to-blue-800/20';
            case 'youtube':
                return 'dark:from-red-900/20 dark:to-orange-900/20';
            case 'tiktok':
                return 'dark:from-gray-900/20 dark:to-gray-800/20';
            case 'telegram':
                return 'dark:from-blue-900/20 dark:to-blue-800/20';
            default:
                return 'dark:from-gray-900/20 dark:to-gray-800/20';
        }
    }

    private function getPlatformDarkBorder($platform)
    {
        switch ($platform) {
            case 'instagram':
                return 'dark:border-pink-800';
            case 'facebook':
                return 'dark:border-blue-800';
            case 'youtube':
                return 'dark:border-red-800';
            case 'tiktok':
                return 'dark:border-gray-700';
            case 'telegram':
                return 'dark:border-blue-800';
            default:
                return 'dark:border-gray-700';
        }
    }

    public function clearHomeServicesCache()
    {
        Cache::forget('home_services');

        $platforms = $this->getHomePlatforms();
        foreach ($platforms as $platform) {
            Cache::forget("home_services_{$platform}");
        }
    }
}
