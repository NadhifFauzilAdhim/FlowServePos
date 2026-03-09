<div class="flex flex-col min-h-[calc(100vh-64px)]">
    @if ($showPaymentMethods)
        {{-- Payment Method Selection --}}
        <div class="flex-1 flex flex-col items-center justify-center p-6 sm:p-8 text-center">
            <div
                class="size-20 rounded-full bg-primary/20 border-2 border-primary/40 flex items-center justify-center mb-6 shadow-[0_0_40px_rgba(212,115,17,0.3)]">
                <span
                    class="material-symbols-outlined text-primary text-4xl drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">payments</span>
            </div>
            <h2 class="text-white text-xl sm:text-2xl font-bold mb-2 drop-shadow-md">Pilih Metode Pembayaran</h2>
            <p class="text-gray-400 text-sm mb-2">Pesanan <strong class="text-primary">{{ $lastOrderNumber }}</strong></p>
            <p class="text-gray-500 text-xs mb-8">Meja #{{ $tableNumber }}</p>

            <div class="flex flex-col gap-4 w-full max-w-sm">
                {{-- Pay at Cashier --}}
                <button wire:click="selectPayment('cashier')"
                    class="w-full p-5 rounded-2xl bg-black/30 backdrop-blur-md border border-white/10 hover:border-primary/50 hover:bg-black/50 transition-all group text-left flex items-center gap-4 shadow-lg">
                    <div
                        class="size-14 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center shrink-0 shadow-[inset_0_0_10px_rgba(16,185,129,0.2)] group-hover:shadow-[inset_0_0_15px_rgba(16,185,129,0.3)]">
                        <span class="material-symbols-outlined text-emerald-400 text-2xl">point_of_sale</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-white font-bold text-base mb-0.5">Bayar di Kasir</h3>
                        <p class="text-gray-400 text-xs">Lakukan pembayaran langsung ke kasir</p>
                    </div>
                    <span
                        class="material-symbols-outlined text-gray-500 group-hover:text-primary transition-colors">chevron_right</span>
                </button>

                {{-- QRIS (Coming Soon) --}}
                <div
                    class="w-full p-5 rounded-2xl bg-black/20 border border-white/5 opacity-50 cursor-not-allowed text-left flex items-center gap-4 relative overflow-hidden">
                    <div
                        class="size-14 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-blue-400/50 text-2xl">qr_code_2</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-gray-400 font-bold text-base mb-0.5">QRIS</h3>
                        <p class="text-gray-600 text-xs">Scan QRIS untuk pembayaran digital</p>
                    </div>
                    <span
                        class="px-2.5 py-1 rounded-full bg-gray-700/50 border border-gray-600/30 text-gray-400 text-[10px] font-bold uppercase tracking-wider">Segera</span>
                </div>
            </div>
        </div>
    @elseif ($orderSuccess)
        {{-- Success Screen --}}
        <div class="flex-1 flex flex-col items-center justify-center p-6 sm:p-8 text-center">
            <div
                class="size-24 rounded-full bg-emerald-500/20 border-2 border-emerald-500/40 flex items-center justify-center mb-6 shadow-[0_0_40px_rgba(16,185,129,0.3)]">
                <span
                    class="material-symbols-outlined text-emerald-400 text-5xl drop-shadow-[0_0_8px_rgba(16,185,129,0.5)]">check_circle</span>
            </div>
            <h2 class="text-white text-xl sm:text-2xl font-bold mb-2 drop-shadow-md">Pesanan Tercatat!</h2>
            <p class="text-gray-400 text-sm mb-6">Silakan menuju kasir untuk pembayaran</p>

            <div
                class="bg-black/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 mb-6 w-full max-w-xs sm:max-w-sm shadow-lg">
                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold mb-1">Nomor Pesanan</p>
                <p class="text-primary text-2xl font-bold drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">
                    {{ $lastOrderNumber }}</p>
                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[16px]">table_restaurant</span>
                    <span class="text-gray-300 text-sm font-medium">Meja #{{ $tableNumber }}</span>
                </div>
            </div>

            <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 mb-8 w-full max-w-xs sm:max-w-sm">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-400 text-xl shrink-0 mt-0.5">info</span>
                    <div class="text-left">
                        <p class="text-amber-300 text-sm font-semibold mb-1">Bayar di Kasir</p>
                        <p class="text-amber-400/80 text-xs leading-relaxed">Tunjukkan nomor pesanan ke kasir. Pesanan
                            akan diproses setelah pembayaran dikonfirmasi.</p>
                    </div>
                </div>
            </div>

            <button wire:click="newOrder"
                class="px-8 py-3 rounded-xl bg-primary border border-primary/50 text-white text-sm font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.3),0_0_24px_rgba(212,115,17,0.5)] flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">restaurant_menu</span>
                Pesan Lagi
            </button>
        </div>
    @else
        @if (!$this->isStoreOpen)
            <div
                class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center">
                <div
                    class="bg-red-500/10 border border-red-500/30 p-8 rounded-3xl flex flex-col items-center max-w-sm shadow-[0_0_40px_rgba(239,68,68,0.2)]">
                    <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-red-500 text-5xl">storefront</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-3">Mohon Maaf, Toko Sedang Tutup</h2>
                    <p class="text-gray-400 text-sm mb-6">Saat ini kami tidak dapat menerima pesanan baru. Silakan
                        kembali lagi nanti saat toko kami sudah buka. Terima kasih!</p>
                </div>
            </div>
        @endif

        {{-- Category Tabs --}}
        <div
            class="flex gap-2 px-4 sm:px-6 py-3 overflow-x-auto no-scrollbar shrink-0 border-b border-white/10 bg-black/5 backdrop-blur-sm">
            <button wire:click="filterByCategory(null)"
                class="flex h-8 sm:h-9 shrink-0 items-center justify-center rounded-full px-4 sm:px-5 text-xs sm:text-sm font-medium transition-all
                {{ is_null($selectedCategory) ? 'bg-primary border border-primary/50 text-white shadow-[inset_0_0_12px_rgba(255,255,255,0.3)]' : 'bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10' }}">
                Semua
            </button>
            @foreach ($categories as $cat)
                <button wire:click="filterByCategory({{ $cat->id }})"
                    class="flex h-8 sm:h-9 shrink-0 items-center justify-center gap-1.5 rounded-full px-4 sm:px-5 text-xs sm:text-sm font-medium transition-all
                    {{ $selectedCategory === $cat->id ? 'bg-primary border border-primary/50 text-white shadow-[inset_0_0_12px_rgba(255,255,255,0.3)]' : 'bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10' }}">
                    @if ($cat->icon)
                        <span class="material-symbols-outlined text-[14px]">{{ $cat->icon }}</span>
                    @endif
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        {{-- Search --}}
        <div class="px-4 sm:px-6 pt-3 shrink-0">
            <div
                class="flex w-full items-stretch rounded-lg border border-white/10 bg-black/40 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] overflow-hidden focus-within:border-primary/50 transition-all">
                <div class="text-gray-400 flex items-center justify-center pl-3">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </div>
                <input wire:model.live.debounce.300ms="searchQuery" type="text"
                    class="flex w-full min-w-0 flex-1 bg-transparent text-white h-9 sm:h-10 px-3 text-sm border-none focus:outline-none focus:ring-0 placeholder:text-gray-500"
                    placeholder="Cari menu..." />
            </div>
        </div>

        {{-- Menu Grid --}}
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 pb-28">
            @if ($menus->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-gray-500">
                    <span class="material-symbols-outlined text-4xl mb-3">search_off</span>
                    <p class="text-sm">Menu tidak ditemukan</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
                    @foreach ($menus as $menu)
                        <div wire:click="addToCart({{ $menu->id }})" wire:key="menu-{{ $menu->id }}"
                            class="flex flex-col cursor-pointer bg-black/20 backdrop-blur-md border border-white/10 rounded-xl p-2.5 sm:p-3 hover:bg-black/40 hover:border-primary/50 transition-all shadow-lg active:scale-95 group">
                            <div
                                class="w-full aspect-square rounded-lg overflow-hidden mb-2 relative shadow-[inset_0_0_15px_rgba(0,0,0,0.5)]">
                                @if ($menu->image)
                                    <div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-500"
                                        style="background-image: url('{{ asset('storage/' . $menu->image) }}');">
                                    </div>
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-primary/20 to-purple-900/20 flex items-center justify-center">
                                        <span
                                            class="material-symbols-outlined text-3xl text-gray-600">{{ $menu->category->icon ?? 'restaurant' }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="px-0.5">
                                <h3
                                    class="text-white text-xs sm:text-sm font-semibold leading-tight mb-0.5 line-clamp-2">
                                    {{ $menu->name }}</h3>
                                <p class="text-gray-400 text-[10px] sm:text-xs line-clamp-1 mb-1 hidden sm:block">
                                    {{ $menu->description }}</p>
                                <p class="text-primary font-bold text-sm sm:text-base">Rp
                                    {{ number_format($menu->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Floating Cart Button --}}
        @if (!empty($cart))
            <div class="fixed bottom-0 left-0 right-0 z-40 p-4 max-w-lg sm:max-w-2xl lg:max-w-5xl xl:max-w-6xl mx-auto">
                <button wire:click="toggleCart"
                    class="w-full h-14 rounded-2xl bg-primary border border-primary/50 text-white font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.3),0_0_30px_rgba(212,115,17,0.5)] flex items-center justify-between px-5">
                    <div class="flex items-center gap-3">
                        <div class="size-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold">
                            {{ $this->cartCount }}
                        </div>
                        <span class="text-sm">Lihat Keranjang</span>
                    </div>
                    <span class="text-base font-bold">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </button>
            </div>
        @endif

        {{-- Cart Drawer --}}
        @if ($showCart)
            <div class="fixed inset-0 z-50 flex flex-col justify-end bg-black/60 backdrop-blur-sm"
                wire:click.self="toggleCart">
                <div
                    class="bg-[#1a1625] border-t border-white/10 rounded-t-3xl max-h-[85vh] flex flex-col w-full max-w-lg sm:max-w-xl mx-auto shadow-2xl">
                    {{-- Cart Header --}}
                    <div class="flex justify-between items-center p-5 border-b border-white/10 shrink-0">
                        <h3 class="text-white text-lg font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">shopping_cart</span>
                            Keranjang ({{ $this->cartCount }})
                        </h3>
                        <button wire:click="toggleCart" class="text-gray-400 hover:text-white transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    {{-- Cart Items --}}
                    <div class="flex-1 overflow-y-auto p-5 flex flex-col gap-4">
                        @foreach ($cart as $index => $item)
                            <div wire:key="cart-{{ $index }}"
                                class="flex gap-3 pb-4 border-b border-white/10 last:border-b-0 last:pb-0">
                                <div
                                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary/20 to-purple-900/20 border border-white/10 shrink-0 flex items-center justify-center overflow-hidden">
                                    @if ($item['image'])
                                        <div class="w-full h-full bg-center bg-no-repeat bg-cover"
                                            style="background-image: url('{{ asset('storage/' . $item['image']) }}');">
                                        </div>
                                    @else
                                        <span class="material-symbols-outlined text-gray-600 text-lg">coffee</span>
                                    @endif
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-white font-semibold text-sm">{{ $item['name'] }}
                                            </h4>
                                            <input wire:change="updateNote({{ $index }}, $event.target.value)"
                                                type="text" value="{{ $item['notes'] }}"
                                                class="text-gray-500 text-xs mt-0.5 bg-transparent border-none p-0 focus:ring-0 focus:outline-none w-full placeholder:text-gray-600"
                                                placeholder="Catatan..." />
                                        </div>
                                        <span class="text-white font-bold text-sm ml-2">Rp
                                            {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <button wire:click="updateQuantity({{ $index }}, -1)"
                                            class="size-7 rounded-full bg-white/5 border border-white/10 text-gray-300 flex items-center justify-center hover:bg-white/10 transition-all">
                                            <span class="material-symbols-outlined text-[14px]">remove</span>
                                        </button>
                                        <span
                                            class="text-white text-sm font-bold w-4 text-center">{{ $item['quantity'] }}</span>
                                        <button wire:click="updateQuantity({{ $index }}, 1)"
                                            class="size-7 rounded-full bg-white/5 border border-white/10 text-gray-300 flex items-center justify-center hover:bg-white/10 transition-all">
                                            <span class="material-symbols-outlined text-[14px]">add</span>
                                        </button>
                                        <button wire:click="removeFromCart({{ $index }})"
                                            class="ml-auto text-gray-500 hover:text-red-400 transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Order Notes --}}
                        <div class="mt-2">
                            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">
                                Catatan Pesanan</label>
                            <textarea wire:model="orderNotes" rows="2"
                                class="w-full rounded-xl border border-white/10 bg-black/40 text-white px-4 py-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] placeholder:text-gray-600 resize-none"
                                placeholder="Contoh: Tidak pedas, tanpa es..."></textarea>
                        </div>
                    </div>

                    {{-- Cart Footer --}}
                    <div class="p-5 bg-black/20 border-t border-white/10 shrink-0">
                        <div class="flex flex-col gap-2 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-gray-200 font-medium">Rp
                                    {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Pajak (8%)</span>
                                <span class="text-gray-200 font-medium">Rp
                                    {{ number_format($this->taxAmount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold mt-1 pt-2 border-t border-white/10">
                                <span class="text-white">Total</span>
                                <span class="text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">Rp
                                    {{ number_format($this->total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button wire:click="openConfirmModal"
                            class="w-full h-14 rounded-xl bg-emerald-600 border border-emerald-500/50 text-white text-base font-bold hover:bg-emerald-500 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.2),0_0_24px_rgba(16,185,129,0.4)] flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">send</span>
                            Kirim Pesanan
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Confirm Modal --}}
        @if ($showConfirmModal)
            <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
                wire:click.self="closeConfirmModal">
                <div class="bg-[#1a1625] border border-white/10 rounded-2xl p-6 w-full max-w-sm shadow-2xl">
                    <div class="text-center mb-6">
                        <div
                            class="size-16 rounded-full bg-amber-500/20 border border-amber-500/30 flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-amber-400 text-3xl">help</span>
                        </div>
                        <h3 class="text-white text-lg font-bold mb-2">Konfirmasi Pesanan?</h3>
                        <p class="text-gray-400 text-sm">Anda akan memesan
                            <strong class="text-white">{{ $this->cartCount }} item</strong> untuk
                            <strong class="text-primary">Meja #{{ $tableNumber }}</strong>
                        </p>
                        <p class="text-primary text-xl font-bold mt-3 drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">
                            Rp {{ number_format($this->total, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="closeConfirmModal"
                            class="flex-1 h-12 rounded-xl bg-white/5 border border-white/10 text-gray-300 text-sm font-bold hover:bg-white/10 transition-all">
                            Batal
                        </button>
                        <button wire:click="submitOrder"
                            class="flex-1 h-12 rounded-xl bg-emerald-600 border border-emerald-500/50 text-white text-sm font-bold hover:bg-emerald-500 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.2)] flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="submitOrder" class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">check</span> Pesan
                            </span>
                            <span wire:loading wire:target="submitOrder">Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
