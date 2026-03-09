<div class="shrink-0 w-full" wire:poll.5s>
    @if ($waitingOrders->count() > 0)
        <div x-data="{ open: true }" class="border-b border-white/10">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-5 py-3 bg-amber-500/10 hover:bg-amber-500/15 transition-colors">
                <div class="flex items-center gap-2">
                    <span
                        class="material-symbols-outlined text-amber-400 text-lg animate-pulse">notifications_active</span>
                    <span class="text-amber-400 text-sm font-bold">QR Order Incoming</span>
                    <span
                        class="bg-amber-500/30 border border-amber-500/40 text-amber-300 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $waitingOrders->count() }}
                    </span>
                </div>
                <span class="material-symbols-outlined text-amber-400 text-lg transition-transform"
                    :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>

            <div x-show="open" x-collapse class="max-h-[280px] overflow-y-auto no-scrollbar">
                @foreach ($waitingOrders as $wo)
                    <div wire:key="waiting-{{ $wo->id }}"
                        class="px-4 py-3 border-b border-white/5 last:border-b-0 hover:bg-white/5 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/20 border border-primary/30 text-primary text-xs font-bold">
                                    <span class="material-symbols-outlined text-[12px]">table_restaurant</span>
                                    Meja #{{ $wo->table_number }}
                                </span>
                                <span class="text-white text-xs font-semibold">{{ $wo->order_number }}</span>
                            </div>
                            <span
                                class="text-gray-500 text-[10px]">{{ $wo->created_at->diffForHumans(null, true, true) }}</span>
                        </div>

                        <div class="mb-2">
                            @foreach ($wo->items as $item)
                                <div class="flex items-center gap-1.5 text-gray-300 text-xs">
                                    <span class="text-primary font-bold">{{ $item->quantity }}x</span>
                                    <span>{{ $item->menu->name }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-white text-sm font-bold">Rp
                                {{ number_format($wo->total, 0, ',', '.') }}</span>
                            <div class="flex gap-1.5">
                                <button wire:click="$parent.openRejectModal({{ $wo->id }})"
                                    class="px-2.5 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-semibold hover:bg-red-500/20 transition-all">
                                    Reject
                                </button>
                                <button wire:click="$parent.confirmQrOrder({{ $wo->id }})"
                                    class="px-2.5 py-1.5 rounded-lg bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-bold hover:bg-emerald-500/30 transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check</span> Confirm
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
