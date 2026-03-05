<div class="p-6">
    <div class="mb-8">
        <h1 class="text-white text-2xl font-bold drop-shadow-md">Order History</h1>
        <p class="text-gray-400 text-sm mt-1">View and manage all orders</p>
    </div>

    {{-- Filters --}}
    <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 mb-6 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">Search</label>
            <div
                class="flex items-stretch rounded-lg border border-white/10 bg-black/40 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] overflow-hidden focus-within:border-primary/50 transition-all">
                <div class="text-gray-400 flex items-center justify-center pl-3">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="flex w-full min-w-0 flex-1 bg-transparent text-white h-9 px-3 text-sm border-none focus:outline-none focus:ring-0 placeholder:text-gray-500"
                    placeholder="Search order number..." />
            </div>
        </div>
        <div class="min-w-[150px]">
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">Status</label>
            <select wire:model.live="statusFilter"
                class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-9 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="min-w-[150px]">
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">From</label>
            <input wire:model.live="dateFrom" type="date"
                class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-9 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
        </div>
        <div class="min-w-[150px]">
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">To</label>
            <input wire:model.live="dateTo" type="date"
                class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-9 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10 bg-black/10">
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Order #</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Date</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Type</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Items</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Total</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Status</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Cashier</th>
                        <th class="text-right text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr wire:key="order-{{ $order->id }}"
                            class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-5 text-white text-sm font-semibold">{{ $order->order_number }}</td>
                            <td class="py-3.5 px-5 text-gray-300 text-sm">{{ $order->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-3.5 px-5">
                                <span
                                    class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/5 border border-white/10 text-gray-300">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-gray-300 text-sm">{{ $order->items->count() }}</td>
                            <td class="py-3.5 px-5 text-white text-sm font-bold">Rp
                                {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-5">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                        'completed' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                        'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusColors[$order->status] ?? '' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-gray-300 text-sm">{{ $order->user->name }}</td>
                            <td class="py-3.5 px-5 text-right">
                                <a href="{{ route('orders.detail', $order) }}" wire:navigate
                                    class="text-primary hover:text-primary-light text-sm font-medium transition-colors">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <span
                                    class="material-symbols-outlined text-4xl text-gray-600 mb-2 block">receipt_long</span>
                                <p class="text-gray-500 text-sm">No orders found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            <div class="px-5 py-4 border-t border-white/10">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
