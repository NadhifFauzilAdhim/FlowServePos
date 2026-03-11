<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ReportService
{
    public function getDashboardMetrics(): array
    {
        return Cache::remember('dashboard_metrics', 30, function () {
            $today = today();

            // Revenue counts all PAID orders today (regardless of kitchen status)
            $orderStats = Order::whereDate('created_at', $today)
                ->select(
                    DB::raw('COUNT(*) as total_orders'),
                    DB::raw('SUM(CASE WHEN payment_status = "paid" THEN total ELSE 0 END) as total_revenue'),
                    DB::raw('SUM(CASE WHEN status = "pending" AND payment_status = "paid" THEN 1 ELSE 0 END) as pending_orders')
                )
                ->first();

            $bestSeller = OrderItem::query()
                ->whereHas('order', fn($q) => $q->whereDate('created_at', $today)->paymentPaid())
                ->select('menu_id', DB::raw('SUM(quantity) as total_qty'))
                ->groupBy('menu_id')
                ->orderByDesc('total_qty')
                ->with('menu')
                ->first();

            return [
                'today_revenue' => (float) ($orderStats->total_revenue ?? 0),
                'today_orders' => (int) ($orderStats->total_orders ?? 0),
                'pending_orders' => (int) ($orderStats->pending_orders ?? 0),
                'best_seller' => $bestSeller?->menu?->name ?? '-',
                'best_seller_qty' => $bestSeller?->total_qty ?? 0,
            ];
        });
    }

    public function getSalesTrend(int $days = 7): array
    {
        return Cache::remember("dashboard_sales_trend_{$days}", 300, function () use ($days) {
            $labels = [];
            $data = [];
            
            $startDate = now()->subDays($days - 1)->startOfDay();
            
            $sales = Order::completed()
                ->where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
                ->groupBy('date')
                ->pluck('total', 'date');

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dateString = $date->format('Y-m-d');
                $labels[] = $date->format('M d');
                $data[] = (float) ($sales[$dateString] ?? 0);
            }

            return ['labels' => $labels, 'data' => $data];
        });
    }

    public function getPopularItems(int $limit = 5, ?Carbon $from = null, ?Carbon $to = null): array
    {
        $cacheKey = "dashboard_popular_items_{$limit}_" . ($from ? $from->format('Ymd') : 'none') . "_" . ($to ? $to->format('Ymd') : 'none');
        
        return Cache::remember($cacheKey, 300, function () use ($limit, $from, $to) {
            $from = $from ?? now()->subDays(30);
            $to = $to ?? now();

            $items = OrderItem::query()
                ->whereHas('order', function ($q) use ($from, $to) {
                    $q->whereBetween('created_at', [$from, $to])->completed();
                })
                ->select('menu_id', DB::raw('SUM(quantity) as total_qty'))
                ->groupBy('menu_id')
                ->orderByDesc('total_qty')
                ->limit($limit)
                ->with('menu')
                ->get();

            return [
                'labels' => $items->pluck('menu.name')->toArray(),
                'data' => $items->pluck('total_qty')->toArray(),
            ];
        });
    }

    public function getSalesReport(string $period = 'daily', ?Carbon $from = null, ?Carbon $to = null): array
    {
        $from = $from ?? now()->subDays(30);
        $to = $to ?? now();

        $groupBy = match ($period) {
            'daily' => 'DATE(created_at)',
            'weekly' => 'YEARWEEK(created_at)',
            'monthly' => 'DATE_FORMAT(created_at, "%Y-%m")',
        };

        $formatLabel = match ($period) {
            'daily' => 'DATE(created_at)',
            'weekly' => 'YEARWEEK(created_at)',
            'monthly' => 'DATE_FORMAT(created_at, "%Y-%m")',
        };

        $results = Order::query()
            ->completed()
            ->whereBetween('created_at', [$from, $to])
            ->select(
                DB::raw("{$groupBy} as period"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as order_count'),
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels' => $results->pluck('period')->toArray(),
            'revenue' => $results->pluck('revenue')->map(fn($v) => (float) $v)->toArray(),
            'order_count' => $results->pluck('order_count')->toArray(),
            'total_revenue' => $results->sum('revenue'),
            'total_orders' => $results->sum('order_count'),
        ];
    }

    public function getBestSellers(int $limit = 10, ?Carbon $from = null, ?Carbon $to = null)
    {
        $from = $from ?? now()->subDays(30);
        $to = $to ?? now();

        return OrderItem::query()
            ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$from, $to])->completed())
            ->select(
                'menu_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->with('menu.category')
            ->get();
    }
}
