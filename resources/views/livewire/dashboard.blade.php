<div class="p-6">
    <div class="mb-8">
        <h1 class="text-white text-2xl font-bold drop-shadow-md">Dashboard</h1>
        <p class="text-gray-400 text-sm mt-1">Overview of today's business performance</p>
    </div>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Today Revenue --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-400 text-sm font-medium">Today's Revenue</span>
                <div class="p-2 rounded-lg bg-emerald-500/20 border border-emerald-500/30">
                    <span class="material-symbols-outlined text-emerald-400 text-xl">payments</span>
                </div>
            </div>
            <p class="text-white text-2xl font-bold drop-shadow-md">Rp
                {{ number_format($metrics['today_revenue'], 0, ',', '.') }}</p>
        </div>

        {{-- Today Orders --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-400 text-sm font-medium">Total Orders</span>
                <div class="p-2 rounded-lg bg-blue-500/20 border border-blue-500/30">
                    <span class="material-symbols-outlined text-blue-400 text-xl">receipt_long</span>
                </div>
            </div>
            <p class="text-white text-2xl font-bold drop-shadow-md">{{ $metrics['today_orders'] }}</p>
        </div>

        {{-- Best Seller --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-400 text-sm font-medium">Best Seller</span>
                <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                    <span class="material-symbols-outlined text-primary text-xl">star</span>
                </div>
            </div>
            <p class="text-white text-xl font-bold drop-shadow-md">{{ $metrics['best_seller'] }}</p>
            <p class="text-gray-400 text-sm mt-1">{{ $metrics['best_seller_qty'] }} sold</p>
        </div>

        {{-- Active Orders --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-400 text-sm font-medium">Pending Orders</span>
                <div class="p-2 rounded-lg bg-amber-500/20 border border-amber-500/30">
                    <span class="material-symbols-outlined text-amber-400 text-xl">pending_actions</span>
                </div>
            </div>
            <p class="text-white text-2xl font-bold drop-shadow-md">{{ $metrics['pending_orders'] }}</p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Sales Trend --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
            <h3 class="text-white text-lg font-bold mb-5">Sales Trend (Last 7 Days)</h3>
            <div id="salesTrendChart" class="h-64"></div>
        </div>

        {{-- Popular Items --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
            <h3 class="text-white text-lg font-bold mb-5">Popular Items</h3>
            <div id="popularItemsChart" class="h-64"></div>
        </div>
    </div>
</div>

@script
    <script>
        // Sales Trend Chart
        const salesEl = document.querySelector('#salesTrendChart');
        if (salesEl) {
            new ApexCharts(salesEl, {
                chart: {
                    type: 'area',
                    height: '100%',
                    background: 'transparent',
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'Manrope, sans-serif',
                },
                series: [{
                    name: 'Revenue',
                    data: @json($salesTrend['data']),
                }],
                labels: @json($salesTrend['labels']),
                colors: ['#d47311'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 100],
                    },
                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
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
                            fontSize: '11px'
                        }
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
                markers: {
                    size: 4,
                    strokeWidth: 0,
                    hover: {
                        size: 6
                    }
                },
                theme: {
                    mode: 'dark'
                },
            }).render();
        }

        // Popular Items Chart
        const popEl = document.querySelector('#popularItemsChart');
        if (popEl) {
            new ApexCharts(popEl, {
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
                    name: 'Qty Sold',
                    data: @json($popularItems['data']),
                }],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 6,
                        barHeight: '60%',
                        distributed: true,
                    },
                },
                colors: ['#d47311', '#a855f7', '#3b82f6', '#10b981', '#f43f5e'],
                labels: @json($popularItems['labels']),
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: 'rgba(255,255,255,0.06)',
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: '#9ca3af',
                            fontSize: '11px'
                        }
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
                            colors: '#e5e7eb',
                            fontSize: '12px',
                            fontWeight: 500
                        }
                    },
                },
                legend: {
                    show: false
                },
                tooltip: {
                    theme: 'dark'
                },
                theme: {
                    mode: 'dark'
                },
            }).render();
        }
    </script>
@endscript
