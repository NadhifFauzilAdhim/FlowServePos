<div class="p-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-white text-2xl font-bold drop-shadow-md">Inventory</h1>
            <p class="text-gray-400 text-sm mt-1">Track stock levels and manage supplies</p>
        </div>
        <div class="flex items-center gap-3">
            @if ($lowStockCount > 0)
                <span
                    class="px-4 py-2 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    {{ $lowStockCount }} low stock
                </span>
            @endif
            <button wire:click="openCreateModal"
                class="px-5 py-2.5 rounded-xl bg-primary border border-primary/50 text-white text-sm font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)] flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span> Add Item
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <div class="mb-6">
        <div
            class="flex max-w-md items-stretch rounded-lg border border-white/10 bg-black/40 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] overflow-hidden focus-within:border-primary/50 transition-all">
            <div class="text-gray-400 flex items-center justify-center pl-3">
                <span class="material-symbols-outlined text-[18px]">search</span>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="flex w-full min-w-0 flex-1 bg-transparent text-white h-10 px-3 text-sm border-none focus:outline-none focus:ring-0 placeholder:text-gray-500"
                placeholder="Search inventory..." />
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10 bg-black/10">
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Item</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">SKU
                        </th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Stock</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">Min
                            Stock</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Cost/Unit</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Status</th>
                        <th class="text-right text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr wire:key="inv-{{ $item->id }}"
                            class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-5 text-white text-sm font-semibold">{{ $item->name }}</td>
                            <td class="py-3.5 px-5 text-gray-400 text-sm font-mono">{{ $item->sku }}</td>
                            <td class="py-3.5 px-5 text-white text-sm font-bold">
                                {{ number_format($item->current_stock, 1) }} {{ $item->unit }}</td>
                            <td class="py-3.5 px-5 text-gray-400 text-sm">{{ number_format($item->min_stock, 1) }}
                                {{ $item->unit }}</td>
                            <td class="py-3.5 px-5 text-gray-300 text-sm">Rp
                                {{ number_format($item->cost_per_unit, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-5">
                                @if ($item->isLowStock())
                                    <span
                                        class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-500/20 text-red-400 border border-red-500/30">
                                        Low Stock
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        In Stock
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openAdjustModal({{ $item->id }})"
                                        class="text-blue-400 hover:text-blue-300 transition-colors"
                                        title="Adjust Stock">
                                        <span class="material-symbols-outlined text-[18px]">tune</span>
                                    </button>
                                    <button wire:click="openEditModal({{ $item->id }})"
                                        class="text-gray-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button wire:click="deleteItem({{ $item->id }})"
                                        wire:confirm="Delete {{ $item->name }}?"
                                        class="text-gray-400 hover:text-red-400 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <span
                                    class="material-symbols-outlined text-4xl text-gray-600 mb-2 block">inventory_2</span>
                                <p class="text-gray-500 text-sm">No inventory items</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="closeModal">
            <div class="bg-[#1a1625] border border-white/10 rounded-2xl p-8 w-full max-w-lg shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-white text-xl font-bold">{{ $editingId ? 'Edit' : 'Add' }} Inventory Item</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white"><span
                            class="material-symbols-outlined">close</span></button>
                </div>
                <form wire:submit="save" class="flex flex-col gap-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-300 text-sm font-medium mb-1.5 block">Name</label>
                            <input wire:model="name" type="text"
                                class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                                placeholder="e.g., Coffee Beans" />
                            @error('name')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="text-gray-300 text-sm font-medium mb-1.5 block">SKU</label>
                            <input wire:model="sku" type="text"
                                class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] font-mono"
                                placeholder="CB-001" />
                            @error('sku')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-gray-300 text-sm font-medium mb-1.5 block">Unit</label>
                            <select wire:model="unit"
                                class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]">
                                <option value="pcs">Pieces</option>
                                <option value="kg">Kilogram</option>
                                <option value="g">Gram</option>
                                <option value="ml">Milliliter</option>
                                <option value="l">Liter</option>
                                <option value="pack">Pack</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-gray-300 text-sm font-medium mb-1.5 block">Current Stock</label>
                            <input wire:model="current_stock" type="number" step="0.1" min="0"
                                class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
                        </div>
                        <div>
                            <label class="text-gray-300 text-sm font-medium mb-1.5 block">Min Stock</label>
                            <input wire:model="min_stock" type="number" step="0.1" min="0"
                                class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
                        </div>
                    </div>
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Cost per Unit (Rp)</label>
                        <input wire:model="cost_per_unit" type="number" step="100" min="0"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
                    </div>
                    <button type="submit"
                        class="w-full h-11 rounded-xl bg-primary border border-primary/50 text-white font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)] mt-2">
                        {{ $editingId ? 'Update' : 'Create' }} Item
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Adjust Stock Modal --}}
    @if ($showAdjustModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="$set('showAdjustModal', false)">
            <div class="bg-[#1a1625] border border-white/10 rounded-2xl p-8 w-full max-w-md shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-white text-xl font-bold">Adjust Stock</h3>
                    <button wire:click="$set('showAdjustModal', false)" class="text-gray-400 hover:text-white"><span
                            class="material-symbols-outlined">close</span></button>
                </div>
                <form wire:submit="adjustStock" class="flex flex-col gap-4">
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Type</label>
                        <select wire:model="adjustType"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]">
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                            <option value="adjustment">Set Exact Amount</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Quantity</label>
                        <input wire:model="adjustQuantity" type="number" step="0.1" min="0"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
                        @error('adjustQuantity')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Notes</label>
                        <textarea wire:model="adjustNotes" rows="2"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white px-3 py-2 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] resize-none"
                            placeholder="Reason for adjustment..."></textarea>
                    </div>
                    <button type="submit"
                        class="w-full h-11 rounded-xl bg-blue-600 border border-blue-500/50 text-white font-bold hover:bg-blue-500 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.2),0_0_15px_rgba(59,130,246,0.4)] mt-2">
                        Adjust Stock
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
