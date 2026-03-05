<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;

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

        return Excel::download(
            new SalesReportExport($period, $from, $to),
            "sales-report-{$period}-{$from->format('Ymd')}-{$to->format('Ymd')}.xlsx"
        );
    }
}
