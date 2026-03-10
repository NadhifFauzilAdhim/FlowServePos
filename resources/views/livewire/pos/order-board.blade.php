<div class="flex flex-1 overflow-hidden h-full" x-data
    @print-receipt.window="
        const receiptEl = document.getElementById('receipt-content');
        if (!receiptEl) return;
        const printWindow = window.open('', '_blank', 'width=400,height=600');
        printWindow.document.write(`<!DOCTYPE html><html><head><meta charset='utf-8'><title>Receipt</title><style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Courier New', monospace; font-size: 12px; color: #000; background: #fff; padding: 8px; width: 80mm; }
            .text-center { text-align: center; }
            .text-xs { font-size: 11px; }
            .text-xl { font-size: 18px; }
            .text-base { font-size: 14px; }
            .font-bold { font-weight: bold; }
            .font-semibold { font-weight: 600; }
            .font-medium { font-weight: 500; }
            .italic { font-style: italic; }
            .flex { display: flex; }
            .justify-between { justify-content: space-between; }
            .mb-2 { margin-bottom: 6px; }
            .mb-3 { margin-bottom: 8px; }
            .mb-4 { margin-bottom: 12px; }
            .mt-0\\.5 { margin-top: 2px; }
            .mt-1 { margin-top: 4px; }
            .mt-2 { margin-top: 6px; }
            .pb-3 { padding-bottom: 8px; }
            .pt-2 { padding-top: 6px; }
            .tracking-tight { letter-spacing: -0.025em; }
            .border-b { border-bottom: 1px dashed #999; }
            .border-b-2 { border-bottom: 2px dashed #999; }
            .border-t { border-top: 1px solid #ccc; }
            .text-gray-400, .text-gray-500 { color: #777; }
            .text-gray-600 { color: #555; }
            .text-gray-700, .text-gray-900 { color: #111; }
            .text-green-600 { color: #16a34a; }
            .text-\\[10px\\] { font-size: 10px; }
            p, div { line-height: 1.5; }
            @media print { body { width: 80mm; margin: 0; padding: 4px; } }
        </style></head><body>`);
        printWindow.document.write(receiptEl.querySelector('.bg-white').innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => { printWindow.print(); printWindow.close(); }, 300);
    ">
    {{-- LEFT: Menu Panel --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        @if (!$this->isStoreOpen)
            <div
                class="absolute inset-0 z-50 bg-black/80 backdrop-blur-sm flex flex-col items-center justify-center p-6 text-center">
                <div
                    class="bg-red-500/10 border border-red-500/30 p-8 rounded-3xl flex flex-col items-center max-w-md shadow-[0_0_40px_rgba(239,68,68,0.2)]">
                    <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-red-400 text-3xl">storefront</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Store is Closed</h2>
                    <p class="text-gray-400 text-sm">You cannot process new orders or accept QR orders at this time.
                        Please open the store from the sidebar to resume operations.</p>
                </div>
            </div>
        @endif
        {{-- Category Filter --}}
        <div
            class="flex gap-3 p-6 overflow-x-auto no-scrollbar shrink-0 border-b border-white/10 bg-black/5 backdrop-blur-sm">
            <button wire:click="filterByCategory(null)"
                class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-sm font-medium leading-normal transition-all
                {{ is_null($selectedCategory) ? 'bg-primary border border-primary/50 text-white shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)]' : 'bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10 hover:border-white/20 shadow-[inset_0_0_8px_rgba(255,255,255,0.05)]' }}">
                All Items
            </button>
            @foreach ($categories as $cat)
                <button wire:click="filterByCategory({{ $cat->id }})"
                    class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-sm font-medium leading-normal transition-all
                    {{ $selectedCategory === $cat->id ? 'bg-primary border border-primary/50 text-white shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)]' : 'bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10 hover:border-white/20 shadow-[inset_0_0_8px_rgba(255,255,255,0.05)]' }}">
                    @if ($cat->icon)
                        <span class="material-symbols-outlined text-[16px]">{{ $cat->icon }}</span>
                    @endif
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        {{-- Search Bar --}}
        <div class="px-6 pt-4 shrink-0">
            <div
                class="flex w-full items-stretch rounded-lg border border-white/10 bg-black/40 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] overflow-hidden focus-within:border-primary/50 focus-within:shadow-[inset_0_0_10px_rgba(212,115,17,0.2)] transition-all">
                <div class="text-gray-400 flex items-center justify-center pl-3">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input wire:model.live.debounce.300ms="searchQuery" type="text"
                    class="form-input flex w-full min-w-0 flex-1 resize-none rounded-lg text-white focus:outline-none focus:ring-0 border-none bg-transparent h-10 placeholder:text-gray-500 px-3 text-sm font-normal leading-normal"
                    placeholder="Search menu items..." />
            </div>
        </div>

        {{-- Menu Grid --}}
        <div class="flex-1 overflow-y-auto no-scrollbar p-6">
            @if ($menus->isEmpty())
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <span class="material-symbols-outlined text-5xl mb-3">search_off</span>
                    <p class="text-sm">No menu items found</p>
                </div>
            @else
                <div class="grid grid-cols-[repeat(auto-fill,minmax(180px,1fr))] gap-5">
                    @foreach ($menus as $menu)
                        <div wire:click="addToCart({{ $menu->id }})" wire:key="menu-{{ $menu->id }}"
                            class="flex flex-col group cursor-pointer bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-3 hover:bg-black/40 hover:border-primary/50 transition-all shadow-lg active:scale-95">
                            <div
                                class="w-full aspect-square rounded-xl overflow-hidden mb-3 relative shadow-[inset_0_0_20px_rgba(0,0,0,0.5)]">
                                @if ($menu->image)
                                    <div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-500"
                                        style="background-image: url('{{ asset('storage/' . $menu->image) }}');">
                                    </div>
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-primary/20 to-purple-900/20 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                                        <span
                                            class="material-symbols-outlined text-4xl text-gray-600">{{ $menu->category->icon ?? 'restaurant' }}</span>
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-50 group-hover:opacity-20 transition-opacity">
                                </div>
                            </div>
                            <div class="px-1">
                                <h3 class="text-white text-sm font-semibold leading-tight mb-1">{{ $menu->name }}</h3>
                                <p class="text-gray-400 text-xs font-normal line-clamp-1 mb-2">{{ $menu->description }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-white font-bold text-base drop-shadow-md">Rp
                                        {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    <button
                                        class="size-7 rounded-full bg-primary/20 border border-primary/30 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white group-hover:shadow-[inset_0_0_10px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.5)] transition-all">
                                        <span class="material-symbols-outlined text-sm drop-shadow-sm">add</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- RIGHT: Cart Panel --}}
    <div
        class="w-[360px] flex flex-col h-full bg-black/20 backdrop-blur-2xl border-l border-white/10 shrink-0 shadow-[-8px_0_32px_-16px_rgba(0,0,0,0.8)] z-20">

        {{-- QR Orders Waiting Confirmation --}}
        <livewire:pos.qr-order-list />

        {{-- Cart Header --}}
        <div class="p-5 border-b border-white/10 shrink-0 bg-black/10">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-white text-xl font-bold leading-tight drop-shadow-md">Current Order</h3>
                @if (!empty($cart))
                    <button wire:click="resetCart"
                        class="text-gray-400 hover:text-red-400 transition-colors text-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">delete_sweep</span> Clear
                    </button>
                @endif
            </div>
            <div class="flex gap-2">
                @foreach (['dine_in' => 'Dine In', 'takeout' => 'Takeout', 'delivery' => 'Delivery'] as $type => $label)
                    <button wire:click="setOrderType('{{ $type }}')"
                        class="flex-1 py-2.5 rounded-lg text-sm font-medium transition-all
                        {{ $orderType === $type ? 'bg-primary border border-primary/50 text-white shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)]' : 'bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10 shadow-[inset_0_0_8px_rgba(255,255,255,0.05)]' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto no-scrollbar p-5 flex flex-col gap-4">
            @forelse ($cart as $index => $item)
                <div wire:key="cart-{{ $index }}" class="flex gap-4 pb-4 border-b border-white/10">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary/20 to-purple-900/20 border border-white/10 shrink-0 shadow-[inset_0_0_10px_rgba(0,0,0,0.5)] flex items-center justify-center overflow-hidden">
                        @if ($item['image'])
                            <div class="w-full h-full bg-center bg-no-repeat bg-cover"
                                style="background-image: url('{{ asset('storage/' . $item['image']) }}');"></div>
                        @else
                            <span class="material-symbols-outlined text-gray-600 text-xl">coffee</span>
                        @endif
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-white font-semibold text-sm drop-shadow-sm">{{ $item['name'] }}</h4>
                                <input wire:change="updateNote({{ $index }}, $event.target.value)"
                                    type="text" value="{{ $item['notes'] }}"
                                    class="text-gray-500 text-xs mt-0.5 bg-transparent border-none p-0 focus:ring-0 focus:outline-none w-full placeholder:text-gray-600"
                                    placeholder="Add note..." />
                            </div>
                            <span class="text-white font-bold text-sm ml-2">Rp
                                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center gap-3 mt-2">
                            <button wire:click="updateQuantity({{ $index }}, -1)"
                                class="size-7 rounded-full bg-white/5 border border-white/10 text-gray-300 flex items-center justify-center hover:bg-white/10 shadow-[inset_0_0_6px_rgba(255,255,255,0.05)] transition-all">
                                <span class="material-symbols-outlined text-[16px]">remove</span>
                            </button>
                            <span class="text-white text-sm font-bold w-4 text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity({{ $index }}, 1)"
                                class="size-7 rounded-full bg-white/5 border border-white/10 text-gray-300 flex items-center justify-center hover:bg-white/10 shadow-[inset_0_0_6px_rgba(255,255,255,0.05)] transition-all">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                            </button>
                            <button wire:click="removeFromCart({{ $index }})"
                                class="ml-auto text-gray-500 hover:text-red-400 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex-1 flex flex-col items-center justify-center text-gray-500">
                    <span class="material-symbols-outlined text-5xl mb-3 opacity-50">shopping_cart</span>
                    <p class="text-sm">Cart is empty</p>
                    <p class="text-xs text-gray-600 mt-1">Click menu items to add</p>
                </div>
            @endforelse
        </div>

        {{-- Cart Footer / Totals --}}
        <div class="p-5 bg-black/20 border-t border-white/10 shrink-0 backdrop-blur-md">
            {{-- Success Message --}}
            @if ($lastOrder && !$showReceiptModal)
                <div
                    class="mb-4 p-3 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        <span class="font-bold">Order {{ $lastOrder['order_number'] }}</span>
                    </div>
                    <p class="text-xs">Change: Rp {{ number_format($lastOrder['change_amount'], 0, ',', '.') }}</p>
                </div>
            @endif

            <div class="flex flex-col gap-2.5 mb-5">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Subtotal</span>
                    <span class="text-gray-200 font-medium">Rp
                        {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Tax</span>
                    <span class="text-gray-200 font-medium">Rp
                        {{ number_format($this->taxAmount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Discount</span>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400 font-medium drop-shadow-[0_0_4px_rgba(52,211,153,0.3)]">-Rp
                        </span>
                        <input wire:model.live="discount" type="number" min="0"
                            class="w-24 text-right text-emerald-400 font-medium bg-transparent border-none p-0 focus:ring-0 focus:outline-none text-sm"
                            placeholder="0" />
                    </div>
                </div>
                <div class="flex justify-between text-lg font-bold mt-2 pt-3 border-t border-white/10">
                    <span class="text-white">Total</span>
                    <span class="text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">Rp
                        {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <button wire:click="openPaymentModal" @if (empty($cart)) disabled @endif
                class="w-full h-14 rounded-xl bg-primary border border-primary/50 text-white text-lg font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.3),0_0_24px_rgba(212,115,17,0.5)] flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                Charge Rp {{ number_format($this->total, 0, ',', '.') }}
                <span
                    class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </button>
        </div>
    </div>

    {{-- Payment Modal --}}
    @if ($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="closePaymentModal">
            <div class="bg-[#1a1625] border border-white/10 rounded-2xl p-8 w-full max-w-md shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-white text-xl font-bold">Confirm Payment</h3>
                    <button wire:click="closePaymentModal" class="text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="mb-6 p-4 rounded-xl bg-black/30 border border-white/10">
                    @php $currentTotal = $payingQrOrderId ? $payingQrOrderData['total'] : $this->total; @endphp
                    <div class="flex justify-between text-lg font-bold mb-2">
                        <span class="text-gray-300">Total</span>
                        <span class="text-primary">Rp {{ number_format($currentTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-gray-400 text-sm">
                        {{ $payingQrOrderId ? $payingQrOrderData['item_count'] : count($cart) }} item(s) •
                        {{ ucfirst(str_replace('_', ' ', $payingQrOrderId ? $payingQrOrderData['order_type'] : $orderType)) }}
                        @if ($payingQrOrderId && $payingQrOrderData['table_number'])
                            • Meja {{ $payingQrOrderData['table_number'] }}
                        @endif
                    </div>
                </div>

                {{-- Payment Method Selector --}}
                <div class="mb-6">
                    <label class="text-gray-300 text-sm font-medium mb-2 block">Payment Method</label>
                    <div class="flex gap-2">
                        <button wire:click="setPosPaymentMethod('cash')"
                            class="flex-1 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2
                            {{ $posPaymentMethod === 'cash' ? 'bg-emerald-600 border border-emerald-500/50 text-white shadow-[inset_0_0_12px_rgba(255,255,255,0.2)]' : 'bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10' }}">
                            <span class="material-symbols-outlined text-[18px]">payments</span>
                            Cash
                        </button>
                        <button wire:click="setPosPaymentMethod('qris')"
                            class="flex-1 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2
                            {{ $posPaymentMethod === 'qris' ? 'bg-blue-600 border border-blue-500/50 text-white shadow-[inset_0_0_12px_rgba(255,255,255,0.2)]' : 'bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10' }}">
                            <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                            QRIS
                        </button>
                    </div>
                </div>

                @if ($posPaymentMethod === 'cash')
                    <div class="mb-6">
                        <label class="text-gray-300 text-sm font-medium mb-2 block">Amount Received (Rp)</label>
                        <input wire:model.live="amountReceived" type="number" min="0" step="1000"
                            class="w-full rounded-xl border border-white/10 bg-black/40 text-white text-2xl font-bold h-14 px-4 text-center focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                            autofocus />
                        @error('amountReceived')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror

                        {{-- Quick amount buttons --}}
                        <div class="grid grid-cols-3 gap-2 mt-3">
                            @foreach ([ceil($currentTotal / 1000) * 1000, ceil($currentTotal / 5000) * 5000, ceil($currentTotal / 10000) * 10000, 50000, 100000, 200000] as $amount)
                                <button wire:click="$set('amountReceived', {{ $amount }})"
                                    class="py-2 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm font-medium hover:bg-white/10 transition-all">
                                    {{ number_format($amount, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if ($amountReceived >= $currentTotal)
                        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-emerald-400">Change</span>
                                <span class="text-emerald-400 drop-shadow-[0_0_4px_rgba(52,211,153,0.3)]">Rp
                                    {{ number_format($this->change, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif

                    <button wire:click="processPayment" @if ($amountReceived < $currentTotal) disabled @endif
                        class="w-full h-14 rounded-xl bg-emerald-600 border border-emerald-500/50 text-white text-lg font-bold hover:bg-emerald-500 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.2),0_0_24px_rgba(16,185,129,0.4)] flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span wire:loading.remove wire:target="processPayment">Complete Payment</span>
                        <span wire:loading wire:target="processPayment">Processing...</span>
                    </button>
                @else
                    {{-- QRIS Payment Info --}}
                    <div class="mb-6 p-4 rounded-xl bg-blue-500/10 border border-blue-500/20">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-400 text-xl shrink-0 mt-0.5">info</span>
                            <div>
                                <p class="text-blue-300 text-sm font-semibold mb-1">QRIS Payment</p>
                                <p class="text-blue-400/80 text-xs leading-relaxed">A Midtrans payment popup will
                                    appear. The customer can scan the QRIS code to complete payment.</p>
                            </div>
                        </div>
                    </div>

                    <button wire:click="processPayment"
                        class="w-full h-14 rounded-xl bg-blue-600 border border-blue-500/50 text-white text-lg font-bold hover:bg-blue-500 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.2),0_0_24px_rgba(59,130,246,0.4)] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">qr_code_2</span>
                        <span wire:loading.remove wire:target="processPayment">Open QRIS Payment</span>
                        <span wire:loading wire:target="processPayment">Preparing...</span>
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- Receipt Modal --}}
    @if ($showReceiptModal && $lastOrder)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm print:bg-white print:backdrop-blur-none"
            wire:click.self="closeReceiptModal">
            <div
                class="bg-[#1a1625] border border-white/10 rounded-2xl w-full max-w-sm shadow-2xl flex flex-col max-h-[90vh] print:max-h-none print:bg-white print:border-none print:shadow-none print:rounded-none print:max-w-none">
                {{-- Modal Header (hidden on print) --}}
                <div class="flex justify-between items-center p-5 border-b border-white/10 shrink-0 print:hidden">
                    <h3 class="text-white text-lg font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-400">check_circle</span>
                        Payment Successful
                    </h3>
                    <button wire:click="closeReceiptModal" class="text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- Receipt Content (printable) --}}
                <div class="overflow-y-auto no-scrollbar flex-1 p-5 print:p-0 print:overflow-visible"
                    id="receipt-content">
                    <div
                        class="bg-white text-gray-900 rounded-xl p-6 font-mono text-sm print:rounded-none print:p-4 print:shadow-none">
                        {{-- Store Header --}}
                        <div class="text-center mb-4 pb-3 border-b-2 border-dashed border-gray-300">
                            <h2 class="text-xl font-bold tracking-tight">☕ FlowServe</h2>
                            <p class="text-xs text-gray-500 mt-1">Jl. Contoh Alamat No. 123</p>
                            <p class="text-xs text-gray-500">Tel: (021) 1234-5678</p>
                        </div>

                        {{-- Order Info --}}
                        <div class="mb-3 pb-3 border-b border-dashed border-gray-300 text-xs">
                            <div class="flex justify-between"><span>No.</span><span
                                    class="font-bold">{{ $lastOrder['order_number'] }}</span></div>
                            <div class="flex justify-between">
                                <span>Tanggal</span><span>{{ $lastOrder['date'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Kasir</span><span>{{ $lastOrder['cashier'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tipe</span><span>{{ $lastOrder['order_type'] }}</span>
                            </div>
                        </div>

                        {{-- Items --}}
                        <div class="mb-3 pb-3 border-b border-dashed border-gray-300">
                            @foreach ($lastOrder['items'] as $item)
                                <div class="mb-2">
                                    <div class="font-semibold text-xs">{{ $item['name'] }}</div>
                                    <div class="flex justify-between text-xs text-gray-600">
                                        <span>{{ $item['quantity'] }} x Rp
                                            {{ number_format($item['unit_price'], 0, ',', '.') }}</span>
                                        <span class="font-medium text-gray-900">Rp
                                            {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                    </div>
                                    @if ($item['notes'])
                                        <div class="text-[10px] text-gray-400 italic">* {{ $item['notes'] }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Totals --}}
                        <div class="mb-3 pb-3 border-b border-dashed border-gray-300 text-xs">
                            <div class="flex justify-between"><span>Subtotal</span><span>Rp
                                    {{ number_format($lastOrder['subtotal'], 0, ',', '.') }}</span></div>
                            <div class="flex justify-between"><span>Pajak
                                    ({{ $lastOrder['tax_rate'] }}%)</span><span>Rp
                                    {{ number_format($lastOrder['tax_amount'], 0, ',', '.') }}</span></div>
                            @if ($lastOrder['discount_amount'] > 0)
                                <div class="flex justify-between text-green-600"><span>Diskon</span><span>-Rp
                                        {{ number_format($lastOrder['discount_amount'], 0, ',', '.') }}</span></div>
                            @endif
                            <div class="flex justify-between font-bold text-base mt-2 pt-2 border-t border-gray-300">
                                <span>TOTAL</span>
                                <span>Rp {{ number_format($lastOrder['total'], 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Payment --}}
                        <div class="mb-4 pb-3 border-b border-dashed border-gray-300 text-xs">
                            <div class="flex justify-between"><span>Metode</span><span
                                    class="font-semibold">{{ $lastOrder['payment_method'] ?? 'Tunai' }}</span></div>
                            <div class="flex justify-between">
                                <span>{{ $lastOrder['payment_method'] === 'QRIS' ? 'Dibayar' : 'Tunai' }}</span><span>Rp
                                    {{ number_format($lastOrder['amount_received'], 0, ',', '.') }}</span></div>
                            @if (($lastOrder['change_amount'] ?? 0) > 0)
                                <div class="flex justify-between font-bold"><span>Kembali</span><span>Rp
                                        {{ number_format($lastOrder['change_amount'], 0, ',', '.') }}</span></div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="text-center text-xs text-gray-500">
                            <p class="font-semibold text-gray-700">Terima kasih!</p>
                            <p class="mt-0.5">Selamat menikmati ☕</p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons (hidden on print) --}}
                <div class="p-5 border-t border-white/10 shrink-0 flex gap-3 print:hidden">
                    <button wire:click="printReceipt"
                        class="flex-1 h-12 rounded-xl bg-white/5 border border-white/10 text-gray-200 text-sm font-bold hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">print</span>
                        Print
                    </button>
                    <button wire:click="closeReceiptModal"
                        class="flex-1 h-12 rounded-xl bg-primary border border-primary/50 text-white text-sm font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.3),0_0_24px_rgba(212,115,17,0.5)] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        New Order
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Reject Modal --}}
    @if ($showRejectModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="closeRejectModal">
            <div
                class="bg-[#1a1625] border border-red-500/30 rounded-2xl p-8 w-full max-w-sm shadow-[0_0_40px_rgba(239,68,68,0.15)] animate-fade-in relative overflow-hidden">
                {{-- Ambient glow --}}
                <div
                    class="absolute top-[-50px] right-[-50px] w-32 h-32 bg-red-500/20 rounded-full blur-[40px] pointer-events-none">
                </div>

                <div class="flex flex-col items-center text-center relative z-10">
                    <div
                        class="w-16 h-16 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mb-5 shadow-[inset_0_0_15px_rgba(239,68,68,0.2)]">
                        <span class="material-symbols-outlined text-red-400 text-3xl">warning</span>
                    </div>
                    <h3 class="text-white text-xl font-bold mb-2">Reject Order</h3>
                    <p class="text-gray-400 text-sm mb-8 leading-relaxed">
                        Are you sure you want to reject order <span
                            class="text-white font-bold">{{ $rejectingQrOrderNumber }}</span>
                        @if ($rejectingQrOrderTable)
                            (Table #{{ $rejectingQrOrderTable }})
                        @endif?
                        This action cannot be undone.
                    </p>
                </div>

                <div class="flex gap-3 relative z-10">
                    <button wire:click="closeRejectModal"
                        class="flex-1 py-3 rounded-xl bg-white/5 border border-white/10 text-gray-300 text-sm font-bold hover:bg-white/10 transition-all">
                        Cancel
                    </button>
                    <button wire:click="rejectQrOrder"
                        class="flex-1 py-3 rounded-xl bg-red-600 border border-red-500/50 text-white text-sm font-bold hover:bg-red-500 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.2),0_0_15px_rgba(239,68,68,0.4)] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                        Yes, Reject
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        // Audio notification for new QR orders
        $wire.on('new-qr-order', () => {
            try {
                const ctx = new(window.AudioContext || window.webkitAudioContext)();
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

        // Handle POS QRIS payment via Midtrans Snap
        $wire.on('open-pos-snap-payment', ({
            snapToken,
            orderId
        }) => {
            if (typeof snap === 'undefined') {
                console.error('Midtrans Snap.js not loaded');
                $wire.posQrisPaymentFailed(orderId);
                return;
            }

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('POS QRIS payment success:', result);
                    $wire.posQrisPaymentSuccess(orderId);
                },
                onPending: function(result) {
                    console.log('POS QRIS payment pending:', result);
                    $wire.posQrisPaymentSuccess(orderId);
                },
                onError: function(result) {
                    console.error('POS QRIS payment error:', result);
                    $wire.posQrisPaymentFailed(orderId);
                },
                onClose: function() {
                    console.log('POS Snap popup closed');
                    $wire.posQrisPaymentFailed(orderId);
                }
            });
        });
    </script>
@endscript
