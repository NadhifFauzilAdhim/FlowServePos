<div class="p-6 lg:p-8">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-white text-2xl font-bold drop-shadow-md">Settings</h1>
        <p class="text-gray-400 text-sm mt-1">Manage global application settings.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Store Status Settings --}}
        <div class="bg-[#1a1625] border border-white/10 rounded-2xl p-6 shadow-xl relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none">
            </div>

            <div class="flex items-center gap-3 mb-6">
                <div
                    class="size-10 rounded-xl bg-primary/20 border border-primary/30 flex items-center justify-center shadow-[inset_0_0_8px_rgba(212,115,17,0.2)]">
                    <span class="material-symbols-outlined text-primary">store</span>
                </div>
                <div>
                    <h2 class="text-white text-lg font-bold">Store Status</h2>
                    <p class="text-gray-400 text-xs">Open or close the store for orders</p>
                </div>
            </div>

            @if (session()->has('success_store'))
                <div
                    class="mb-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    {{ session('success_store') }}
                </div>
            @endif

            <div class="bg-black/20 rounded-xl border border-white/5 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-200 font-semibold mb-1">
                            {{ $isStoreOpen ? 'Open' : 'Closed' }}
                        </h3>
                        <p class="text-gray-500 text-xs mt-1">
                            {{ $isStoreOpen ? 'Customers can place orders via POS and QR.' : 'Orders via POS and QR are currently rejected.' }}
                        </p>
                    </div>

                    {{-- Toggle Switch --}}
                    <button wire:click="toggleStoreStatus"
                        class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none transition-all mr-2">
                        <span aria-hidden="true"
                            class="pointer-events-none absolute h-full w-full rounded-full transition-colors ease-in-out duration-200 
                            {{ $isStoreOpen ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.5)]' : 'bg-gray-700' }}">
                        </span>
                        <span aria-hidden="true"
                            class="pointer-events-none absolute left-1 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition-transform ease-in-out duration-200 
                            {{ $isStoreOpen ? 'translate-x-5' : 'translate-x-0' }}">
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Tax Settings --}}
        <div class="bg-[#1a1625] border border-white/10 rounded-2xl p-6 shadow-xl relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none">
            </div>

            <div class="flex items-center gap-3 mb-6">
                <div
                    class="size-10 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center shadow-[inset_0_0_8px_rgba(245,158,11,0.2)]">
                    <span class="material-symbols-outlined text-amber-400">percent</span>
                </div>
                <div>
                    <h2 class="text-white text-lg font-bold">Tax Rate</h2>
                    <p class="text-gray-400 text-xs">Set the tax rate that will be automatically added to all new orders
                    </p>
                </div>
            </div>

            @if (session()->has('success_tax'))
                <div
                    class="mb-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    {{ session('success_tax') }}
                </div>
            @endif

            <form wire:submit="saveTaxRate" class="flex flex-col gap-4">
                <div>
                    <label class="text-gray-300 text-sm font-medium mb-1.5 block">Store Tax Rate (%)</label>
                    <div class="relative w-full max-w-xs">
                        <input wire:model="taxRate" type="number" step="0.01" min="0" max="100"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-11 px-3 pl-10 text-sm focus:border-amber-500/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-bold">%</span>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs mt-2 leading-relaxed">This percentage will be automatically applied
                        to all new orders.</p>
                    @error('taxRate')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="h-11 px-6 rounded-xl bg-amber-600/20 border border-amber-500/40 text-amber-400 font-bold hover:bg-amber-600/40 transition-all shadow-[inset_0_0_12px_rgba(245,158,11,0.1),0_0_15px_rgba(245,158,11,0.05)] flex items-center gap-2">
                        <span wire:loading.remove wire:target="saveTaxRate" class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Save Tax
                        </span>
                        <span wire:loading wire:target="saveTaxRate">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
