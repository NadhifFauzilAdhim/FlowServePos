<div class="p-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('orders') }}" wire:navigate class="text-gray-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <h1 class="text-white text-2xl font-bold drop-shadow-md">Order {{ $order->order_number }}</h1>
                @php
                    $statusColors = [
                        'pending' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                        'completed' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                        'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
                    ];
                @endphp
                <span
                    class="inline-flex px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$order->status] ?? '' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <p class="text-gray-400 text-sm">{{ $order->created_at->format('D, d M Y • H:i') }}</p>
        </div>
        @if ($order->status === 'pending')
            <button wire:click="cancelOrder" wire:confirm="Are you sure you want to cancel this order?"
                class="px-4 py-2 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm font-medium hover:bg-red-500/30 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">cancel</span> Cancel Order
            </button>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Items --}}
        <div class="lg:col-span-2 bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
            <h3 class="text-white text-lg font-bold mb-5">Order Items</h3>
            <div class="flex flex-col gap-4">
                @foreach ($order->items as $item)
                    <div class="flex gap-4 pb-4 border-b border-white/10 last:border-b-0 last:pb-0">
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary/20 to-purple-900/20 border border-white/10 shrink-0 flex items-center justify-center overflow-hidden">
                            @if ($item->menu->image)
                                <div class="w-full h-full bg-center bg-no-repeat bg-cover"
                                    style="background-image: url('{{ asset('storage/' . $item->menu->image) }}');">
                                </div>
                            @else
                                <span class="material-symbols-outlined text-gray-600">coffee</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-white font-semibold text-sm">{{ $item->menu->name }}</h4>
                                    @if ($item->notes)
                                        <p class="text-gray-500 text-xs mt-0.5">{{ $item->notes }}</p>
                                    @endif
                                </div>
                                <span class="text-white font-bold text-sm">Rp
                                    {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-gray-400 text-xs mt-1">{{ $item->quantity }}x @ Rp
                                {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
            <h3 class="text-white text-lg font-bold mb-5">Summary</h3>

            <div class="flex flex-col gap-3 mb-5">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Order Type</span>
                    <span
                        class="text-gray-200 font-medium">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Cashier</span>
                    <span class="text-gray-200 font-medium">{{ $order->user->name }}</span>
                </div>
            </div>

            <hr class="border-white/10 mb-5">

            <div class="flex flex-col gap-2.5">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Subtotal</span>
                    <span class="text-gray-200 font-medium">Rp
                        {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Tax ({{ $order->tax_rate }}%)</span>
                    <span class="text-gray-200 font-medium">Rp
                        {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Discount</span>
                    <span class="text-emerald-400 font-medium">-Rp
                        {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold mt-2 pt-3 border-t border-white/10">
                    <span class="text-white">Total</span>
                    <span class="text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">Rp
                        {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <hr class="border-white/10 my-5">

            <div class="flex flex-col gap-2.5">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Received</span>
                    <span class="text-gray-200 font-medium">Rp
                        {{ number_format($order->amount_received, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Change</span>
                    <span class="text-emerald-400 font-medium">Rp
                        {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
