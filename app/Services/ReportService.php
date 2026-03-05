<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getDashboardMetrics(): array
    {
        $today = today();

        $todayRevenue = Order::today()->completed()->sum('total');
        $todayOrders = Order::today()->count();
        $pendingOrders = Order::today()->pending()->count();

        $bestSeller = OrderItem::query()
            ->whereHas('order', fn($q) => $q->whereDate('created_at', $today)->completed())
            ->select('menu_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->with('menu')
            ->first();

        return [
            'today_revenue' => $todayRevenue,
            'today_orders' => $todayOrders,
            'pending_orders' => $pendingOrders,
            'best_seller' => $bestSeller?->menu?->name ?? '-',
            'best_seller_qty' => $bestSeller?->total_qty ?? 0,
        ];
    }

    public function getSalesTrend(int $days = 7): array
    {
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
            $data[] = (float) Order::whereDate('created_at', $date)->completed()->sum('total');
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getPopularItems(int $limit = 5, ?Carbon $from = null, ?Carbon $to = null): array
    {
        $from = $from ?? now()->subDays(30);
        $to = $to ?? now();

        $items = OrderItem::query()
            ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$from, $to])->completed())
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
