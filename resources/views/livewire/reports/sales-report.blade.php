<div class="p-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-white text-2xl font-bold drop-shadow-md">Sales Reports</h1>
            <p class="text-gray-400 text-sm mt-1">Business performance insights</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.pdf', ['period' => $period, 'from' => $dateFrom, 'to' => $dateTo]) }}"
                class="px-4 py-2.5 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm font-medium hover:bg-red-500/30 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span> Export PDF
            </a>
            <a href="{{ route('reports.excel', ['period' => $period, 'from' => $dateFrom, 'to' => $dateTo]) }}"
                class="px-4 py-2.5 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm font-medium hover:bg-emerald-500/30 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">table_view</span> Export Excel
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div
        class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">Period</label>
            <select wire:model.live="period"
                class="rounded-lg border border-white/10 bg-black/40 text-white h-9 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">From</label>
            <input wire:model.live="dateFrom" type="date"
                class="rounded-lg border border-white/10 bg-black/40 text-white h-9 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
        </div>
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">To</label>
            <input wire:model.live="dateTo" type="date"
                class="rounded-lg border border-white/10 bg-black/40 text-white h-9 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg">
            <span class="text-gray-400 text-sm font-medium">Total Revenue</span>
            <p class="text-white text-3xl font-bold mt-2 drop-shadow-md">Rp
                {{ number_format($salesData['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg">
            <span class="text-gray-400 text-sm font-medium">Total Orders</span>
            <p class="text-white text-3xl font-bold mt-2 drop-shadow-md">{{ number_format($salesData['total_orders']) }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Revenue Chart --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
            <h3 class="text-white text-lg font-bold mb-5">Revenue Chart</h3>
            <div id="revenueChart" class="h-72"></div>
        </div>

        {{-- Best Sellers --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
            <h3 class="text-white text-lg font-bold mb-5">Best Selling Items</h3>
            <div class="flex flex-col gap-3">
                @forelse ($bestSellers as $index => $item)
                    <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-white/5' : '' }}">
                        <span class="text-gray-500 text-sm font-bold w-6 text-center">#{{ $index + 1 }}</span>
                        <div class="flex-1">
                            <p class="text-white text-sm font-semibold">{{ $item->menu->name }}</p>
                            <p class="text-gray-500 text-xs">{{ $item->menu->category->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-white text-sm font-bold">{{ $item->total_qty }} sold</p>
                            <p class="text-gray-400 text-xs">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@script
    <script>
        const chartEl = document.querySelector('#revenueChart');
        if (chartEl) {
            new ApexCharts(chartEl, {
                chart: {
                    type: 'bar',
                    height: '100%',
                    background: 'transparent',
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'Manrope, sans-serif',
                },
                series: [{
                    name: 'Revenue',
                    data: @json($salesData['revenue']),
                }],
                labels: @json($salesData['labels']),
                colors: ['#d47311'],
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '55%',
                    },
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        opacityFrom: 0.85,
                        opacityTo: 0.65,
                        stops: [0, 100],
                    },
                },
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: 'rgba(255,255,255,0.06)',
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: '#9ca3af',
                            fontSize: '10px'
                        },
                        rotate: -45,
                        rotateAlways: false,
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#9ca3af',
                            fontSize: '11px'
                        },
                        formatter: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v),
                    },
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                    },
                },
                theme: {
                    mode: 'dark'
                },
            }).render();
        }
    </script>
@endscript
