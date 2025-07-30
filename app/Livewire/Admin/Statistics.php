<?php

namespace App\Livewire\Admin;

use App\Models\ApiProvider;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\LivewireToast;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.main')]
class Statistics extends Component
{
    use LivewireToast;
    public string $tab = 'provider';
    public string $duration = 'thismonth';
    public string $metaTitle = "Statistics";

    protected $queryString = ['tab', 'duration'];

    public $durationList = [
        'yesterday' => 'Yesterday',
        'thisweek' => 'This Week',
        'lastweek' => 'Last Week',
        'thismonth' => 'This Month',
        'lastmonth' => 'Last Month',
        'thisyear' => 'This Year',
        'lastyear' => 'Last Year',
    ];
    function changeTab($tab)
    {
        $this->tab = $tab;
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
    public function getProviderStatistics()
    {
        $dateRange = $this->getDateRange($this->duration);
        $today = Carbon::today();

        // Get today's stats
        $todayStats = Order::selectRaw('
                 api_provider_id,
                 SUM(price) as sales_today,
                 SUM(profit) as profit_today,
                 COUNT(*) as orders_today
             ')
            ->whereDate('created_at', $today)
            ->whereIn('status', ['completed', 'partial', 'processing'])
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
            ->whereIn('status', ['completed', 'partial', 'processing'])
            ->whereNotNull('api_provider_id')
            ->groupBy('api_provider_id')
            ->get()
            ->keyBy('api_provider_id');

        // Get all providers
        $providers = ApiProvider::all();
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

        // Sort by sales period (highest first)
        usort($statistics, function ($a, $b) {
            return $b['sales_period'] <=> $a['sales_period'];
        });

        return $statistics;
    }

    /**
     * Get user statistics
     */
    public function getUserStatistics()
    {
        $dateRange = $this->getDateRange($this->duration);
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

        // Get order stats (today and period)
        $todayOrderStats = Order::selectRaw('
                user_id,
                COUNT(*) as orders_today
            ')
            ->whereDate('created_at', $today)
            ->whereIn('status', ['completed', 'partial', 'processing'])
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
            ->whereIn('status', ['completed', 'partial', 'processing'])
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        // Get lifetime order stats
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

            // Calculate balance (deposits - spending)
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

        // Sort by total deposits (highest first) and limit to top 30
        usort($statistics, function ($a, $b) {
            return $b['total_deposits'] <=> $a['total_deposits'];
        });

        return array_slice($statistics, 0, 30);
    }

    /**
     * Get service statistics
     */
    public function getServiceStatistics()
    {
        $dateRange = $this->getDateRange($this->duration);

        // Get period stats from orders
        $periodStats = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereIn('status', ['completed', 'partial', 'processing'])
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
        $lifetimeStats = Order::whereIn('status', ['completed', 'partial', 'processing'])
            ->whereNotNull('service_id')
            ->selectRaw('
                service_id,
                COUNT(*) as lifetime_orders
            ')
            ->groupBy('service_id')
            ->get()
            ->keyBy('service_id');

        // Get all relevant service IDs that have had orders
        $serviceIds = $periodStats->keys()->merge($lifetimeStats->keys())->unique();

        // Get the services with their relationships
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

        // Sort by sales period (highest first) and limit to top 50
        usort($statistics, function ($a, $b) {
            return $b['sales_period'] <=> $a['sales_period'];
        });

        return array_slice($statistics, 0, 50);
    }

    /**
     * Get order statistics
     */
    public function getOrderStatistics(): array
    {
        $dateRange = $this->getDateRange($this->duration);

        // 1. EFFICIENTLY FETCH DATA: Run a single query to get all necessary data for the period.
        $orders = Order::query()
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select('price', 'profit', 'status', 'type', 'is_drip_feed', 'created_at')
            ->get();

        // Handle the edge case where no orders exist for the period.
        if ($orders->isEmpty()) {
            return $this->getEmptyOrderStats();
        }

        // 2. CALCULATE KPIs: Process the collection to get key performance indicators.
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('price');
        $totalProfit = $orders->sum('profit');
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // 3. PROCESS DISTRIBUTIONS: Group the collection to get breakdowns.
        $statusDistribution = $orders->groupBy('status')->map->count()->sortDesc();

        $typeDistribution = collect([
            'default' => $orders->where('is_drip_feed', false)->where('type', '!=', 'subscription')->count(),
            'drip_feed' => $orders->where('is_drip_feed', true)->count(),
            'subscription' => $orders->where('type', 'subscription')->count(),
        ])->filter(fn($count) => $count > 0); // Only show types that exist

        // 4. PROCESS TRENDS: Group by time to find top-performing slots.
        $busiestHours = $orders
            ->groupBy(fn($order) => $order->created_at->format('H')) // Group by 24-hour format
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
    public function render()
    {
        if (empty($this->duration)) {
            $this->duration = 'thismonth';
        }
        $providerStats = [];
        $userStats = [];
        $serviceStats = [];
        $orderStats = [];
        if ($this->tab == 'provider') {
            $providerStats = $this->getProviderStatistics();
        } else if ($this->tab == 'user') {
            $userStats = $this->getUserStatistics();
        } else if ($this->tab == 'service') {
            $serviceStats = $this->getServiceStatistics();
        } else if ($this->tab == 'order') {
            $orderStats = $this->getOrderStatistics();
        }

        return view('livewire.admin.statistics', [
            'providerStats' => $providerStats,
            'userStats' => $userStats,
            'serviceStats' => $serviceStats,
            'orderStats' => $orderStats,
        ]);
    }
}
