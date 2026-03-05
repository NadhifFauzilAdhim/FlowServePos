<div class="p-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-white text-2xl font-bold drop-shadow-md">Categories</h1>
            <p class="text-gray-400 text-sm mt-1">Manage menu categories</p>
        </div>
        <button wire:click="openCreateModal"
            class="px-5 py-2.5 rounded-xl bg-primary border border-primary/50 text-white text-sm font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)] flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">add</span> Add Category
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($categories as $category)
            <div wire:key="cat-{{ $category->id }}"
                class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg hover:border-white/20 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                            <span
                                class="material-symbols-outlined text-primary text-xl">{{ $category->icon ?? 'category' }}</span>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base">{{ $category->name }}</h3>
                            <p class="text-gray-500 text-xs">{{ $category->menus_count }} items</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button wire:click="openEditModal({{ $category->id }})"
                            class="text-gray-400 hover:text-primary transition-colors p-1">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </button>
                        <button wire:click="deleteCategory({{ $category->id }})"
                            wire:confirm="Delete '{{ $category->name }}'? All menu items in this category will also be deleted."
                            class="text-gray-400 hover:text-red-400 transition-colors p-1">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-gray-500 text-xs">
                    <span class="material-symbols-outlined text-[14px]">sort</span>
                    Sort order: {{ $category->sort_order }}
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center">
                <span class="material-symbols-outlined text-4xl text-gray-600 mb-2 block">category</span>
                <p class="text-gray-500 text-sm">No categories yet</p>
            </div>
        @endforelse
    </div>

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="closeModal">
            <div class="bg-[#1a1625] border border-white/10 rounded-2xl p-8 w-full max-w-md shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-white text-xl font-bold">{{ $editingId ? 'Edit' : 'Add' }} Category</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form wire:submit="save" class="flex flex-col gap-4">
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Name</label>
                        <input wire:model="name" type="text"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                            placeholder="e.g., Hot Coffee" />
                        @error('name')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Icon (Material Symbol)</label>
                        <input wire:model="icon" type="text"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                            placeholder="e.g., coffee, bakery_dining" />
                        @if ($icon)
                            <div class="mt-2 flex items-center gap-2 text-gray-400 text-sm">
                                <span class="material-symbols-outlined text-primary">{{ $icon }}</span> Preview
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-1.5 block">Sort Order</label>
                        <input wire:model="sort_order" type="number" min="0"
                            class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
                    </div>
                    <button type="submit"
                        class="w-full h-11 rounded-xl bg-primary border border-primary/50 text-white font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)] mt-2">
                        {{ $editingId ? 'Update' : 'Create' }} Category
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
