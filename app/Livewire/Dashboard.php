<?php

namespace App\Livewire;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $reportService = app(ReportService::class);

        return view('livewire.dashboard', [
            'metrics' => $reportService->getDashboardMetrics(),
            'salesTrend' => $reportService->getSalesTrend(7),
            'popularItems' => $reportService->getPopularItems(5),
        ]);
    }
}
