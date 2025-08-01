<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Service;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\LivewireToast;
use DB;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.main')]
class Dashboard extends Component
{
    use LivewireToast;

    // Meta
    public string $metaTitle;

    // --- All Stats Properties ---
    public $totalUsers;
    public $totalUserBalance;
    public $totalReferralBalance;
    public $depositsToday;
    public $totalDeposits;
    public $profitsToday;
    public $profits7Days;
    public $profits30Days;
    public $allProfits;
    public $totalTickets;
    public $unreadTickets;

    // --- Order Stats Properties ---
    public $totalOrders;
    public $ordersToday;
    public $pendingOrders;
    public $processingOrders;
    public $completedOrders;
    public $inProgressOrders;
    public $partialOrders;
    public $refundedOrders;
    public $canceledOrders;

    // --- Table Data Properties ---
    public $recentUsers = [];
    public $bestServices = [];
    public $recentOrders = [];
    public $orderTrendPeriod = 7;
    public array $orderStatusData = [];
    public array $orderStatusLabels = [];
    public array $orderTrendData = [];
    public array $orderTrendLabels = [];

    /**
     * Mount the component and load all initial data.
     */
    public function mount()
    {
        $this->metaTitle = 'Dashboard';
        $this->loadData();
        $this->loadChartData();
    }

    /**
     * Query the database and populate all public properties with caching.
     */
    public function loadData()
    {
        $this->totalUsers = Cache::remember('admin.dashboard.total_users', 300, fn() => User::count());
        $this->totalUserBalance = Cache::remember('admin.dashboard.total_user_balance', 300, fn() => User::sum('balance'));

        $this->totalReferralBalance = Cache::remember('admin.dashboard.total_referral_balance', 600, function () {
            return User::sum('bonus');
        });

        $this->depositsToday = Cache::remember('admin.dashboard.deposits_today', 60, function () {
            return Transaction::where('service', 'deposit')->whereDate('created_at', today())->sum('amount');
        });

        $this->totalDeposits = Cache::remember('admin.dashboard.total_deposits', 300, function () {
            return Transaction::where('service', 'deposit')->sum('amount');
        });

        // Cache profit stats - today's profits cache for 1 minute, others for 5 minutes
        $this->profitsToday = Cache::remember('admin.dashboard.profits_today', 60, function () {
            return Order::whereDate('created_at', today())->sum('profit');
        });

        $this->profits7Days = Cache::remember('admin.dashboard.profits_7days', 300, function () {
            return Order::where('created_at', '>=', now()->subDays(7))->sum('profit');
        });

        $this->profits30Days = Cache::remember('admin.dashboard.profits_30days', 300, function () {
            return Order::where('created_at', '>=', now()->subDays(30))->sum('profit');
        });

        $this->allProfits = Cache::remember('admin.dashboard.all_profits', 600, fn() => Order::sum('profit'));

        $this->totalTickets = Cache::remember('admin.dashboard.total_tickets', 120, fn() => SupportTicket::count());
        $this->unreadTickets = Cache::remember('admin.dashboard.unread_tickets', 60, fn() => SupportTicket::open()->count());

        $this->totalOrders = Cache::remember('admin.dashboard.total_orders', 300, fn() => Order::count());
        $this->ordersToday = Cache::remember('admin.dashboard.orders_today', 60, function () {
            return Order::whereDate('created_at', today())->count();
        });

        $orderStatusCounts = Cache::remember('admin.dashboard.order_status_counts', 120, function () {
            return [
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'completed' => Order::where('status', 'completed')->count(),
                'inprogress' => Order::where('status', 'inprogress')->count(),
                'partial' => Order::where('status', 'partial')->count(),
                'refunded' => Order::where('status', 'refunded')->count(),
                'canceled' => Order::where('status', 'canceled')->count(),
            ];
        });

        $this->pendingOrders = $orderStatusCounts['pending'];
        $this->processingOrders = $orderStatusCounts['processing'];
        $this->completedOrders = $orderStatusCounts['completed'];
        $this->inProgressOrders = $orderStatusCounts['inprogress'];
        $this->partialOrders = $orderStatusCounts['partial'];
        $this->refundedOrders = $orderStatusCounts['refunded'];
        $this->canceledOrders = $orderStatusCounts['canceled'];

        $this->recentOrders = Cache::remember('admin.dashboard.recent_orders', 120, function () {
            return Order::with(['user', 'service', 'provider'])
                ->latest()
                ->take(5)
                ->get();
        });

        $this->recentUsers = Cache::remember('admin.dashboard.recent_users', 120, function () {
            return User::latest()->take(5)->get();
        });

        $this->bestServices = Cache::remember('admin.dashboard.best_services', 300, function () {
            return Service::with('provider')
                ->withCount('orders')
                ->orderByDesc('orders_count')
                ->take(5)
                ->get();
        });
    }

    public function updatedOrderTrendPeriod()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $cacheKey = "admin.dashboard.chart_data.{$this->orderTrendPeriod}";
        
        $statusCounts = Order::query()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $this->orderStatusLabels = $statusCounts->pluck('status')->map(fn($status) => ucfirst($status))->toArray();
        $this->orderStatusData = $statusCounts->pluck('count')->toArray();

        // Get order trend data grouped by status for last 7 days
        $orderTrend = Order::query()
            ->select(DB::raw('DATE(created_at) as date'), 'status', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays($this->orderTrendPeriod))
            ->groupBy('date', 'status')
            ->orderBy('date', 'asc')
            ->get();

        // Create date range for last 7 days
        $period = now()->subDays($this->orderTrendPeriod - 1)->toPeriod(now());
        $dateRange = [];
        foreach ($period as $date) {
            $dateRange[] = $date->format('Y-m-d');
            $formatedDate[] = $date->format('m/d');
        }

        // Get unique statuses
        $statuses = ['pending', 'processing', 'completed', 'inprogress', 'partial', 'refunded', 'canceled'];

        // Prepare data for each status
        $seriesData = [];
        foreach ($statuses as $status) {
            $statusData = [];
            foreach ($dateRange as $date) {
                $count = $orderTrend->where('date', $date)->where('status', $status)->first();
                $statusData[] = $count ? $count->count : 0;
            }
            $seriesData[] = [
                'name' => ucfirst($status),
                'data' => $statusData
            ];
        }

        $this->orderTrendLabels = $formatedDate;
        $this->orderTrendData = $seriesData;
    }

    /**
     * Force refresh all dashboard data by clearing cache.
     */
    public function refreshData()
    {
        $this->clearDashboardCache();
        $this->loadData();
        $this->toast('Dashboard data refreshed successfully!', 'success');
    }

    /**
     * Clear all dashboard related cache keys.
     */
    private function clearDashboardCache()
    {
        $cacheKeys = [
            'admin.dashboard.total_users',
            'admin.dashboard.total_user_balance',
            'admin.dashboard.total_referral_balance',
            'admin.dashboard.deposits_today',
            'admin.dashboard.total_deposits',
            'admin.dashboard.profits_today',
            'admin.dashboard.profits_7days',
            'admin.dashboard.profits_30days',
            'admin.dashboard.all_profits',
            'admin.dashboard.total_tickets',
            'admin.dashboard.unread_tickets',
            'admin.dashboard.total_orders',
            'admin.dashboard.orders_today',
            'admin.dashboard.order_status_counts',
            'admin.dashboard.recent_orders',
            'admin.dashboard.recent_users',
            'admin.dashboard.best_services',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'orders'   => $this->recentOrders,
            'users'    => $this->recentUsers,
            'services' => $this->bestServices,
        ]);
    }
}
