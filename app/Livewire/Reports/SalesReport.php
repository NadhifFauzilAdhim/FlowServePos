<?php

namespace App\Livewire\Reports;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SalesReport extends Component
{
    public string $period = 'daily';
    public string $dateFrom = '';
    public string $dateTo = '';

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $reportService = app(ReportService::class);
        $from = $this->dateFrom ? \Carbon\Carbon::parse($this->dateFrom) : null;
        $to = $this->dateTo ? \Carbon\Carbon::parse($this->dateTo) : null;

        return view('livewire.reports.sales-report', [
            'salesData' => $reportService->getSalesReport($this->period, $from, $to),
            'bestSellers' => $reportService->getBestSellers(10, $from, $to),
        ]);
    }
}
