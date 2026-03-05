<?php

namespace App\Exports;

use App\Services\ReportService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesReportExport implements FromArray, WithHeadings, WithTitle
{
    public function __construct(
        private string $period,
        private Carbon $from,
        private Carbon $to,
    ) {}

    public function array(): array
    {
        $reportService = app(ReportService::class);
        $salesData = $reportService->getSalesReport($this->period, $this->from, $this->to);

        $rows = [];
        for ($i = 0; $i < count($salesData['labels']); $i++) {
            $rows[] = [
                $salesData['labels'][$i],
                $salesData['order_count'][$i],
                'Rp ' . number_format($salesData['revenue'][$i], 0, ',', '.'),
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Period', 'Total Orders', 'Revenue'];
    }

    public function title(): string
    {
        return 'Sales Report';
    }
}
