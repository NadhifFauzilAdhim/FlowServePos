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
            <div class="h-64">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        {{-- Popular Items --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
            <h3 class="text-white text-lg font-bold mb-5">Popular Items</h3>
            <div class="h-64">
                <canvas id="popularItemsChart"></canvas>
            </div>
        </div>
    </div>
</div>

@script
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        // Sales Trend Chart
        const salesCtx = document.getElementById('salesTrendChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($salesTrend['labels']),
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: @json($salesTrend['data']),
                        borderColor: '#d47311',
                        backgroundColor: 'rgba(212, 115, 17, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#d47311',
                        pointBorderColor: '#d47311',
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.05)'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    family: 'Manrope'
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255,255,255,0.05)'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    family: 'Manrope'
                                },
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Popular Items Chart
        const popCtx = document.getElementById('popularItemsChart');
        if (popCtx) {
            new Chart(popCtx, {
                type: 'bar',
                data: {
                    labels: @json($popularItems['labels']),
                    datasets: [{
                        label: 'Quantity Sold',
                        data: @json($popularItems['data']),
                        backgroundColor: [
                            'rgba(212, 115, 17, 0.7)',
                            'rgba(168, 85, 247, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(244, 63, 94, 0.7)',
                        ],
                        borderColor: [
                            '#d47311',
                            '#a855f7',
                            '#3b82f6',
                            '#10b981',
                            '#f43f5e',
                        ],
                        borderWidth: 1,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.05)'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    family: 'Manrope'
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#e5e7eb',
                                font: {
                                    family: 'Manrope',
                                    weight: 500
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endscript
