<?php

namespace App\Services;

use App\Models\ApiProvider;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Cache;
use Carbon\Carbon;

class StatisticsService
{
    private const CACHE_TTL = 900; // 15 minutes
    private const HEAVY_QUERY_CACHE_TTL = 1800; // 30 minutes
    private const PROVIDER_CACHE_TTL = 3600; // 1 hour

    /**
     * Generate cache key for statistics
     */
    private function getCacheKey(string $type, string $duration, array $additionalParams = []): string
    {
        $dateRange = $this->getDateRange($duration);
        $params = array_merge([
            'type' => $type,
            'duration' => $duration,
            'start' => $dateRange['start']->format('Y-m-d H:i'),
            'end' => $dateRange['end']->format('Y-m-d H:i'),
        ], $additionalParams);

        return 'stats_' . md5(json_encode($params));
    }

    /**
     * Get date range based on duration selection
     */
    private function getDateRange($duration = 'thismonth')
    {
        $now = Carbon::now();

        return match ($duration) {
            'yesterday' => [
                'start' => $now->copy()->yesterday()->startOfDay(),
                'end' => $now->copy()->yesterday()->endOfDay()
            ],
            'thisweek' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek()
            ],
            'thismonth' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth()
            ],
            'thisyear' => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfYear()
            ],
            'lastweek' => [
                'start' => $now->copy()->subWeek()->startOfWeek(),
                'end' => $now->copy()->subWeek()->endOfWeek()
            ],
            'lastmonth' => [
                'start' => $now->copy()->subMonth()->startOfMonth(),
                'end' => $now->copy()->subMonth()->endOfMonth()
            ],
            'lastyear' => [
                'start' => $now->copy()->subYear()->startOfYear(),
                'end' => $now->copy()->subYear()->endOfYear()
            ],
            default => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth()
            ]
        };
    }

    /**
     * Get provider statistics
     */
    public function getProviderStatistics($duration = 'thismonth')
    {
        $cacheKey = $this->getCacheKey('provider', $duration);
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($duration) {
            return $this->buildProviderStatistics($duration);
        });
    }

    private function buildProviderStatistics($duration): array
    {
        $dateRange = $this->getDateRange($duration);
        $today = Carbon::today();

        // Get today's stats
        $todayStats = Order::selectRaw('
                 api_provider_id,
                 SUM(price) as sales_today,
                 SUM(profit) as profit_today,
                 COUNT(*) as orders_today
             ')
            ->whereDate('created_at', $today)
            ->whereIn('status', ['completed', 'partial', 'processing', 'inprogress'])
            ->whereNotNull('api_provider_id')
            ->groupBy('api_provider_id')
            ->get()
            ->keyBy('api_provider_id');

        // Get period stats
        $periodStats = Order::selectRaw('
                 api_provider_id,
                 SUM(price) as sales_period,
                 SUM(profit) as profit_period,
                 COUNT(*) as orders_period
             ')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereIn('status', ['completed', 'partial', 'processing', 'inprogress'])
            ->whereNotNull('api_provider_id')
            ->groupBy('api_provider_id')
            ->get()
            ->keyBy('api_provider_id');

        $providers = Cache::remember('api_providers_list', now()->addHours(1), function () {
            return ApiProvider::all();
        });
        $statistics = [];

        foreach ($providers as $provider) {
            $todayStat = $todayStats->get($provider->id);
            $periodStat = $periodStats->get($provider->id);

            $statistics[] = [
                'provider' => $provider,
                'sales_today' => $todayStat->sales_today ?? 0,
                'sales_period' => $periodStat->sales_period ?? 0,
                'profit_today' => $todayStat->profit_today ?? 0,
                'profit_period' => $periodStat->profit_period ?? 0,
                'orders_today' => $todayStat->orders_today ?? 0,
                'orders_period' => $periodStat->orders_period ?? 0,
            ];
        }

        usort($statistics, function ($a, $b) {
            return $b['sales_period'] <=> $a['sales_period'];
        });

        return $statistics;
    }

    /**
     * Get user statistics
     */
    public function getUserStatistics($duration = 'thismonth')
    {
        $cacheKey = $this->getCacheKey('user', $duration);
        return Cache::remember($cacheKey, self::HEAVY_QUERY_CACHE_TTL, function () use ($duration) {
            return $this->buildUserStatistics($duration);
        });
    }

    private function buildUserStatistics($duration): array
    {
        $dateRange = $this->getDateRange($duration);
        $today = Carbon::today();

        // Get transaction stats (deposits and spending)
        $transactionStats = Transaction::selectRaw('
                user_id,
                SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_deposits,
                SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_spent,
                SUM(CASE WHEN type = "credit" AND DATE(created_at) = ? THEN amount ELSE 0 END) as deposits_today,
                SUM(CASE WHEN type = "debit" AND DATE(created_at) = ? THEN amount ELSE 0 END) as spent_today,
                SUM(CASE WHEN type = "credit" AND created_at BETWEEN ? AND ? THEN amount ELSE 0 END) as deposits_period,
                SUM(CASE WHEN type = "debit" AND created_at BETWEEN ? AND ? THEN amount ELSE 0 END) as spent_period
            ', [$today, $today, $dateRange['start'], $dateRange['end'], $dateRange['start'], $dateRange['end']])
            ->whereIn('status', ['successful', 'completed']) // Only successful transactions
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $todayOrderStats = Order::selectRaw('
                user_id,
                COUNT(*) as orders_today
            ')
            ->whereDate('created_at', $today)
            ->whereIn('status', ['completed', 'partial', 'processing', 'inprogress'])
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $periodOrderStats = Order::selectRaw('
                user_id,
                COUNT(*) as orders_period,
                AVG(price) as avg_order_value,
                MAX(created_at) as last_order_date
            ')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereIn('status', ['completed', 'partial', 'processing', 'inprogress'])
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $userIds = $transactionStats->keys()->merge($periodOrderStats->keys())->unique();
        $lifetimeOrderStats = Order::selectRaw('
                user_id,
                COUNT(*) as lifetime_orders,
                MIN(created_at) as first_order_date
            ')
            ->whereIn('user_id', $userIds)
            ->whereIn('status', ['completed', 'partial', 'processing'])
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        // Get users with their details
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');
        $statistics = [];

        foreach ($users as $user) {
            $transactionStat = $transactionStats->get($user->id);
            $todayOrderStat = $todayOrderStats->get($user->id);
            $periodOrderStat = $periodOrderStats->get($user->id);
            $lifetimeOrderStat = $lifetimeOrderStats->get($user->id);

            $totalDeposits = $transactionStat->total_deposits ?? 0;
            $totalSpent = $transactionStat->total_spent ?? 0;
            $calculatedBalance = $totalDeposits - $totalSpent;

            $statistics[] = [
                'user' => $user,
                'total_deposits' => $totalDeposits,
                'total_spent' => $totalSpent,
                'current_balance' => $user->balance ?? $calculatedBalance, // Use user balance if available
                'deposits_today' => $transactionStat->deposits_today ?? 0,
                'spent_today' => $transactionStat->spent_today ?? 0,
                'deposits_period' => $transactionStat->deposits_period ?? 0,
                'spent_period' => $transactionStat->spent_period ?? 0,
                'orders_today' => $todayOrderStat->orders_today ?? 0,
                'orders_period' => $periodOrderStat->orders_period ?? 0,
                'avg_order_value' => $periodOrderStat->avg_order_value ?? 0,
                'lifetime_orders' => $lifetimeOrderStat->lifetime_orders ?? 0,
                'last_order_date' => $periodOrderStat->last_order_date ?? null,
                'first_order_date' => $lifetimeOrderStat->first_order_date ?? null,
            ];
        }

        usort($statistics, function ($a, $b) {
            return $b['total_deposits'] <=> $a['total_deposits'];
        });

        return array_slice($statistics, 0, 30);
    }


    /**
     * Get service statistics
     */
    public function getServiceStatistics($duration = 'thismonth')
    {
        $cacheKey = $this->getCacheKey('service', $duration);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($duration) {
            return $this->buildServiceStatistics($duration);
        });
    }

    private function buildServiceStatistics($duration): array
    {
        $dateRange = $this->getDateRange($duration);

        $periodStats = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereIn('status', ['completed', 'partial', 'processing', 'inprogress'])
            ->whereNotNull('service_id')
            ->selectRaw('
                service_id,
                COUNT(*) as orders_period,
                SUM(price) as sales_period,
                SUM(profit) as profit_period
            ')
            ->groupBy('service_id')
            ->get()
            ->keyBy('service_id');

        // Get lifetime order stats
        $lifetimeStats = Order::whereIn('status', ['completed', 'partial', 'processing', 'inprogress'])
            ->whereNotNull('service_id')
            ->selectRaw('
                service_id,
                COUNT(*) as lifetime_orders
            ')
            ->groupBy('service_id')
            ->get()
            ->keyBy('service_id');

        $serviceIds = $periodStats->keys()->merge($lifetimeStats->keys())->unique();

        $services = Service::with(['category', 'provider'])
            ->whereIn('id', $serviceIds)
            ->get();

        $statistics = [];

        foreach ($services as $service) {
            $periodStat = $periodStats->get($service->id);
            $lifetimeStat = $lifetimeStats->get($service->id);

            $statistics[] = [
                'service' => $service,
                'orders_period' => $periodStat->orders_period ?? 0,
                'sales_period' => $periodStat->sales_period ?? 0,
                'profit_period' => $periodStat->profit_period ?? 0,
                'lifetime_orders' => $lifetimeStat->lifetime_orders ?? 0,
            ];
        }

        usort($statistics, function ($a, $b) {
            return $b['sales_period'] <=> $a['sales_period'];
        });

        return array_slice($statistics, 0, 50);
    }

    /**
     * Get order statistics
     */
    public function getOrderStatistics($duration = 'thismonth'): array
    {
        $cacheKey = $this->getCacheKey('order', $duration);
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($duration) {
            return $this->buildOrderStatistics($duration);
        });
    }

    private function buildOrderStatistics($duration): array
    {
        $dateRange = $this->getDateRange($duration);

        $orders = Order::query()
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select('price', 'profit', 'status', 'type', 'is_drip_feed', 'created_at')
            ->get();

        if ($orders->isEmpty()) {
            return $this->getEmptyOrderStats();
        }
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('price');
        $totalProfit = $orders->sum('profit');
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $statusDistribution = $orders->groupBy('status')->map->count()->sortDesc();

        $typeDistribution = collect([
            'default' => $orders->where('is_drip_feed', false)->where('type', '!=', 'subscription')->count(),
            'drip_feed' => $orders->where('is_drip_feed', true)->count(),
            'subscription' => $orders->where('type', 'subscription')->count(),
        ])->filter(fn($count) => $count > 0);

        $busiestHours = $orders
            ->groupBy(fn($order) => $order->created_at->format('H'))
            ->map->count()
            ->sortDesc()
            ->take(5);

        $topDays = $orders
            ->groupBy(fn($order) => $order->created_at->format('Y-m-d'))
            ->map(fn($dailyOrders) => [
                'count' => $dailyOrders->count(),
                'revenue' => $dailyOrders->sum('price'),
            ])
            ->sortByDesc('count')
            ->take(5);

        return [
            'kpi' => [
                'total_revenue' => $totalRevenue,
                'total_profit' => $totalProfit,
                'total_orders' => $totalOrders,
                'avg_order_value' => $avgOrderValue,
            ],
            'status_distribution' => $statusDistribution,
            'type_distribution' => $typeDistribution,
            'busiest_hours' => $busiestHours,
            'top_days' => $topDays,
        ];
    }

    private function getEmptyOrderStats(): array
    {
        return [
            'kpi' => [
                'total_revenue' => 0,
                'total_profit' => 0,
                'total_orders' => 0,
                'avg_order_value' => 0,
            ],
            'status_distribution' => collect(),
            'type_distribution' => collect(),
            'busiest_hours' => collect(),
            'top_days' => collect(),
        ];
    }

    /**
     * Clear specific cache
     */
    public function clearCache($type = null, $duration = null): void
    {
        if ($type && $duration) {
            $cacheKey = $this->getCacheKey($type, $duration);
            Cache::forget($cacheKey);
        } else {
            // Clear only statistics-related cache keys  
            $types = ['provider', 'user', 'service', 'order'];
            $durations = ['yesterday', 'thisweek', 'lastweek', 'thismonth', 'lastmonth', 'thisyear', 'lastyear'];

            foreach ($types as $cacheType) {
                foreach ($durations as $cacheDuration) {
                    $cacheKey = $this->getCacheKey($cacheType, $cacheDuration);
                    Cache::forget($cacheKey);
                }
            }
        }
    }

    /**
     * Warm up cache for common statistics
     */
    public function warmCache(): void
    {
        $durations = ['thismonth', 'thisweek', 'yesterday'];
        $types = ['provider', 'user', 'service', 'order'];

        foreach ($types as $type) {
            foreach ($durations as $duration) {
                match ($type) {
                    'provider' => $this->getProviderStatistics($duration),
                    'user' => $this->getUserStatistics($duration),
                    'service' => $this->getServiceStatistics($duration),
                    'order' => $this->getOrderStatistics($duration),
                };
            }
        }
    }
}
