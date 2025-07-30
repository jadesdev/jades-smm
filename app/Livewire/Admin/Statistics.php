<?php

namespace App\Livewire\Admin;

use App\Services\StatisticsService;
use App\Traits\LivewireToast;
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

    public function render(StatisticsService $statisticsService)
    {
        if (empty($this->duration)) {
            $this->duration = 'thismonth';
        }
        $providerStats = [];
        $userStats = [];
        $serviceStats = [];
        $orderStats = [];
        if ($this->tab == 'provider') {
            $providerStats = $statisticsService->getProviderStatistics($this->duration);
        } else if ($this->tab == 'user') {
            $userStats = $statisticsService->getUserStatistics($this->duration);
        } else if ($this->tab == 'service') {
            $serviceStats = $statisticsService->getServiceStatistics($this->duration);
        } else if ($this->tab == 'order') {
            $orderStats = $statisticsService->getOrderStatistics($this->duration);
        }

        return view('livewire.admin.statistics', [
            'providerStats' => $providerStats,
            'userStats' => $userStats,
            'serviceStats' => $serviceStats,
            'orderStats' => $orderStats,
        ]);
    }
}
