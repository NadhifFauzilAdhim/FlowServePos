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
            <div class="h-72">
                <canvas id="revenueChart"></canvas>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($salesData['labels']),
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: @json($salesData['revenue']),
                        backgroundColor: 'rgba(212, 115, 17, 0.6)',
                        borderColor: '#d47311',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.05)'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    family: 'Manrope',
                                    size: 10
                                },
                                maxRotation: 45
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
                                callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                            }
                        }
                    }
                }
            });
        }
    </script>
@endscript
