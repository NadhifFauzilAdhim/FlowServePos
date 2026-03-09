<div class="p-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-white text-2xl font-bold drop-shadow-md">Menu Management</h1>
            <p class="text-gray-400 text-sm mt-1">Manage your café menu items</p>
        </div>
        <div>
            <button wire:click="openCreateModal"
                class="px-5 py-2.5 rounded-xl bg-primary border border-primary/50 text-white text-sm font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)] flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span> Add Menu
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
                placeholder="Search menu items..." />
        </div>
    </div>

    {{-- Menu Table --}}
    <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10 bg-black/10">
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Item</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Category</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Price</th>
                        <th class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Status</th>
                        <th class="text-right text-gray-400 text-xs font-semibold uppercase tracking-wider py-4 px-5">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr wire:key="menu-{{ $menu->id }}"
                            class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary/20 to-purple-900/20 border border-white/10 shrink-0 overflow-hidden flex items-center justify-center">
                                        @if ($menu->image)
                                            <div class="w-full h-full bg-center bg-no-repeat bg-cover"
                                                style="background-image: url('{{ asset('storage/' . $menu->image) }}');">
                                            </div>
                                        @else
                                            <span
                                                class="material-symbols-outlined text-gray-600 text-sm">restaurant</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-semibold">{{ $menu->name }}</p>
                                        <p class="text-gray-500 text-xs line-clamp-1">{{ $menu->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-5 text-gray-300 text-sm">{{ $menu->category->name }}</td>
                            <td class="py-3 px-5 text-white text-sm font-bold">Rp
                                {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td class="py-3 px-5">
                                <span
                                    class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $menu->is_available ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                    {{ $menu->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td class="py-3 px-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $menu->id }})"
                                        class="text-gray-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button wire:click="deleteMenu({{ $menu->id }})"
                                        wire:confirm="Delete {{ $menu->name }}?"
                                        class="text-gray-400 hover:text-red-400 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <span
                                    class="material-symbols-outlined text-4xl text-gray-600 mb-2 block">restaurant_menu</span>
                                <p class="text-gray-500 text-sm">No menu items found</p>
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
                    <h3 class="text-white text-xl font-bold">{{ $editingId ? 'Edit' : 'Add' }} Menu Item</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit="save" class="flex flex-col gap-4">
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Name</label>
                        <input wire:model="name" type="text"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                            placeholder="e.g., Caramel Macchiato" />
                        @error('name')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Category</label>
                        <select wire:model="menu_category_id"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]">
                            <option value="">Select category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('menu_category_id')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Price (Rp)</label>
                        <input wire:model="price" type="number" step="500" min="0"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                            placeholder="25000" />
                        @error('price')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Description</label>
                        <textarea wire:model="description" rows="2"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white px-3 py-2 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] resize-none"
                            placeholder="Short description..."></textarea>
                    </div>
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Image</label>
                        <input wire:model="image" type="file" accept="image/*"
                            class="w-full text-gray-400 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/20 file:text-primary hover:file:bg-primary/30" />
                        @error('image')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <input wire:model="is_available" type="checkbox" id="is_available"
                            class="form-checkbox size-4 rounded bg-black/40 border border-white/10 text-primary focus:ring-primary/30" />
                        <label for="is_available" class="text-gray-300 text-sm">Available for order</label>
                    </div>
                    <button type="submit"
                        class="w-full h-11 rounded-xl bg-primary border border-primary/50 text-white font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)] mt-2">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update' : 'Create' }} Menu
                            Item</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
