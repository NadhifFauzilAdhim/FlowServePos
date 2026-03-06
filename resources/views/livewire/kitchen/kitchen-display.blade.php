<div wire:poll.5s class="flex flex-col h-full">
    {{-- Column Headers --}}
    <div class="grid grid-cols-3 gap-0 border-b border-white/10 shrink-0">
        <div class="px-5 py-3 flex items-center justify-between bg-amber-500/5 border-r border-white/10">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-400 text-xl">new_releases</span>
                <span class="text-amber-400 text-sm font-bold uppercase tracking-wider">New Orders</span>
            </div>
            <span
                class="bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-bold px-2.5 py-0.5 rounded-full">
                {{ $pendingOrders->count() }}
            </span>
        </div>
        <div class="px-5 py-3 flex items-center justify-between bg-blue-500/5 border-r border-white/10">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-400 text-xl">skillet</span>
                <span class="text-blue-400 text-sm font-bold uppercase tracking-wider">Preparing</span>
            </div>
            <span
                class="bg-blue-500/20 border border-blue-500/30 text-blue-400 text-xs font-bold px-2.5 py-0.5 rounded-full">
                {{ $preparingOrders->count() }}
            </span>
        </div>
        <div class="px-5 py-3 flex items-center justify-between bg-emerald-500/5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-400 text-xl">check_circle</span>
                <span class="text-emerald-400 text-sm font-bold uppercase tracking-wider">Ready to Serve</span>
            </div>
            <span
                class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-bold px-2.5 py-0.5 rounded-full">
                {{ $readyOrders->count() }}
            </span>
        </div>
    </div>

    {{-- Columns --}}
    <div class="grid grid-cols-3 gap-0 flex-1 overflow-hidden">
        {{-- PENDING Column --}}
        <div class="border-r border-white/10 overflow-y-auto p-4 flex flex-col gap-4">
            @forelse ($pendingOrders as $order)
                @php
                    $elapsed = $order->created_at->diffInMinutes(now());
                    $urgency =
                        $elapsed < 5
                            ? 'border-amber-500/30'
                            : ($elapsed < 10
                                ? 'border-yellow-500/50'
                                : 'border-red-500/50');
                    $urgencyBg = $elapsed < 5 ? 'bg-amber-500/5' : ($elapsed < 10 ? 'bg-yellow-500/5' : 'bg-red-500/5');
                    $timerColor = $elapsed < 5 ? 'text-gray-400' : ($elapsed < 10 ? 'text-yellow-400' : 'text-red-400');
                @endphp
                <div wire:key="pending-{{ $order->id }}"
                    class="rounded-2xl border {{ $urgency }} {{ $urgencyBg }} backdrop-blur-md p-4 shadow-lg animate-fade-in">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-white text-lg font-bold">{{ $order->order_number }}</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span
                                    class="text-gray-400 text-xs">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
                                @if ($order->table_number)
                                    <span class="text-gray-600">•</span>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/20 border border-primary/30 text-primary text-xs font-bold">
                                        <span class="material-symbols-outlined text-[12px]">table_restaurant</span>
                                        Meja #{{ $order->table_number }}
                                    </span>
                                @endif
                                <span class="text-gray-600">•</span>
                                <span class="text-gray-400 text-xs">{{ $order->user?->name ?? 'Guest' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="{{ $timerColor }} text-sm font-bold font-mono flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">timer</span>
                                {{ $elapsed }}m
                            </span>
                            <span class="text-gray-500 text-xs">{{ $order->created_at->format('H:i') }}</span>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="mb-4 flex flex-col gap-1.5">
                        @foreach ($order->items as $item)
                            <div class="flex items-start gap-2">
                                <span class="text-primary font-bold text-sm min-w-[24px]">{{ $item->quantity }}x</span>
                                <div class="flex-1">
                                    <span class="text-white text-sm font-medium">{{ $item->menu->name }}</span>
                                    @if ($item->notes)
                                        <p class="text-amber-400/80 text-xs mt-0.5 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px]">sticky_note_2</span>
                                            {{ $item->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($order->notes)
                        <div
                            class="mb-3 px-3 py-2 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs">
                            <span class="material-symbols-outlined text-[12px] mr-1">info</span> {{ $order->notes }}
                        </div>
                    @endif

                    {{-- Action --}}
                    <button wire:click="markPreparing({{ $order->id }})"
                        class="w-full py-2.5 rounded-xl bg-blue-600 border border-blue-500/50 text-white text-sm font-bold hover:bg-blue-500 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.15),0_0_15px_rgba(59,130,246,0.3)] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">skillet</span> Start Preparing
                    </button>
                </div>
            @empty
                <div class="flex-1 flex flex-col items-center justify-center text-gray-600">
                    <span class="material-symbols-outlined text-5xl mb-2 opacity-50">check_circle</span>
                    <p class="text-sm">No new orders</p>
                </div>
            @endforelse
        </div>

        {{-- PREPARING Column --}}
        <div class="border-r border-white/10 overflow-y-auto p-4 flex flex-col gap-4">
            @forelse ($preparingOrders as $order)
                @php
                    $elapsed = $order->created_at->diffInMinutes(now());
                @endphp
                <div wire:key="preparing-{{ $order->id }}"
                    class="rounded-2xl border border-blue-500/30 bg-blue-500/5 backdrop-blur-md p-4 shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-white text-lg font-bold">{{ $order->order_number }}</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span
                                    class="text-gray-400 text-xs">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
                                @if ($order->table_number)
                                    <span class="text-gray-600">•</span>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/20 border border-primary/30 text-primary text-xs font-bold">
                                        <span class="material-symbols-outlined text-[12px]">table_restaurant</span>
                                        Meja #{{ $order->table_number }}
                                    </span>
                                @endif
                                <span class="text-gray-600">•</span>
                                <span class="text-gray-400 text-xs">{{ $order->user?->name ?? 'Guest' }}</span>
                            </div>
                        </div>
                        <span class="text-blue-400 text-sm font-bold font-mono flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">timer</span>
                            {{ $elapsed }}m
                        </span>
                    </div>

                    <div class="mb-4 flex flex-col gap-1.5">
                        @foreach ($order->items as $item)
                            <div class="flex items-start gap-2">
                                <span class="text-primary font-bold text-sm min-w-[24px]">{{ $item->quantity }}x</span>
                                <div class="flex-1">
                                    <span class="text-white text-sm font-medium">{{ $item->menu->name }}</span>
                                    @if ($item->notes)
                                        <p class="text-blue-400/80 text-xs mt-0.5">
                                            <span class="material-symbols-outlined text-[12px]">sticky_note_2</span>
                                            {{ $item->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button wire:click="markReady({{ $order->id }})"
                        class="w-full py-2.5 rounded-xl bg-emerald-600 border border-emerald-500/50 text-white text-sm font-bold hover:bg-emerald-500 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.15),0_0_15px_rgba(16,185,129,0.3)] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span> Mark Ready
                    </button>
                </div>
            @empty
                <div class="flex-1 flex flex-col items-center justify-center text-gray-600">
                    <span class="material-symbols-outlined text-5xl mb-2 opacity-50">skillet</span>
                    <p class="text-sm">Nothing preparing</p>
                </div>
            @endforelse
        </div>

        {{-- READY Column --}}
        <div class="overflow-y-auto p-4 flex flex-col gap-4">
            @forelse ($readyOrders as $order)
                @php
                    $elapsed = $order->created_at->diffInMinutes(now());
                @endphp
                <div wire:key="ready-{{ $order->id }}"
                    class="rounded-2xl border border-emerald-500/30 bg-emerald-500/5 backdrop-blur-md p-4 shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-white text-lg font-bold">{{ $order->order_number }}</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span
                                    class="text-gray-400 text-xs">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
                                @if ($order->table_number)
                                    <span class="text-gray-600">•</span>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/20 border border-primary/30 text-primary text-xs font-bold">
                                        <span class="material-symbols-outlined text-[12px]">table_restaurant</span>
                                        Meja #{{ $order->table_number }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <span class="text-emerald-400 text-sm font-bold font-mono flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">timer</span>
                            {{ $elapsed }}m
                        </span>
                    </div>

                    <div class="mb-4 flex flex-col gap-1.5">
                        @foreach ($order->items as $item)
                            <div class="flex items-start gap-2">
                                <span class="text-primary font-bold text-sm min-w-[24px]">{{ $item->quantity }}x</span>
                                <span class="text-white text-sm font-medium">{{ $item->menu->name }}</span>
                            </div>
                        @endforeach
                    </div>

                    <button wire:click="markCompleted({{ $order->id }})"
                        class="w-full py-2.5 rounded-xl bg-primary border border-primary/50 text-white text-sm font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.2),0_0_15px_rgba(212,115,17,0.4)] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">done_all</span> Complete & Serve
                    </button>
                </div>
            @empty
                <div class="flex-1 flex flex-col items-center justify-center text-gray-600">
                    <span class="material-symbols-outlined text-5xl mb-2 opacity-50">room_service</span>
                    <p class="text-sm">No orders ready</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@script
    <script>
        // Audio notification for new orders
        $wire.on('new-kitchen-order', () => {
            try {
                const ctx = new(window.AudioContext || window.webkitAudioContext)();
                // Play two-tone chime
                [440, 660].forEach((freq, i) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.2);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime + i * 0.2);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + i * 0.2 + 0.4);
                    osc.start(ctx.currentTime + i * 0.2);
                    osc.stop(ctx.currentTime + i * 0.2 + 0.4);
                });
            } catch (e) {}
        });
    </script>
@endscript
