<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportExportController extends Controller
{
    public function pdf(Request $request)
    {
        $reportService = app(ReportService::class);
        $period = $request->get('period', 'daily');
        $from = $request->get('from') ? Carbon::parse($request->get('from')) : now()->subDays(30);
        $to = $request->get('to') ? Carbon::parse($request->get('to')) : now();

        $salesData = $reportService->getSalesReport($period, $from, $to);
        $bestSellers = $reportService->getBestSellers(10, $from, $to);

        $pdf = Pdf::loadView('reports.sales-pdf', compact('salesData', 'bestSellers', 'period', 'from', 'to'));

        return $pdf->download("sales-report-{$period}-{$from->format('Ymd')}-{$to->format('Ymd')}.pdf");
    }

    public function excel(Request $request)
    {
        $period = $request->get('period', 'daily');
        $from = $request->get('from') ? Carbon::parse($request->get('from')) : now()->subDays(30);
        $to = $request->get('to') ? Carbon::parse($request->get('to')) : now();

        $reportService = app(\App\Services\ReportService::class);
        $salesData = $reportService->getSalesReport($period, $from, $to);
        
        $fileName = "sales-report-{$period}-{$from->format('Ymd')}-{$to->format('Ymd')}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Period', 'Total Orders', 'Revenue'];

        $callback = function() use($salesData, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            for ($i = 0; $i < count($salesData['labels']); $i++) {
                $row = [
                    $salesData['labels'][$i],
                    $salesData['order_count'][$i],
                    'Rp ' . number_format($salesData['revenue'][$i], 0, ',', '.'),
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
