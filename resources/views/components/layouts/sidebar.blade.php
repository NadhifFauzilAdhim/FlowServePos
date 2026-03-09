        {{-- Sidebar --}}
        <nav
            class="flex h-full w-[240px] flex-col justify-between border-r border-white/10 bg-black/20 backdrop-blur-xl p-4 shrink-0 shadow-[4px_0_24px_-16px_rgba(0,0,0,0.5)] z-20">
            <div class="flex flex-col gap-8">
                {{-- User Profile --}}
                <div class="flex gap-3 items-center">
                    <div
                        class="flex items-center justify-center rounded-full size-10 bg-primary/20 border border-primary/30 text-primary font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-white text-base font-medium leading-normal">{{ auth()->user()->name }}</h1>
                        <p class="text-primary text-sm font-normal leading-normal drop-shadow-sm">
                            {{ auth()->user()->roles->first()?->name ?? 'Staff' }}
                        </p>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="flex flex-col gap-2">
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                        <span
                            class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">dashboard</span>
                        <span
                            class="{{ request()->routeIs('dashboard') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Dashboard</span>
                    </a>

                    <a href="{{ route('pos') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('pos') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                        <span
                            class="material-symbols-outlined {{ request()->routeIs('pos') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">point_of_sale</span>
                        <span
                            class="{{ request()->routeIs('pos') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">POS</span>
                    </a>

                    <a href="{{ route('kitchen') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kitchen') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                        <span
                            class="material-symbols-outlined {{ request()->routeIs('kitchen') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">soup_kitchen</span>
                        <span
                            class="{{ request()->routeIs('kitchen') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Kitchen</span>
                    </a>

                    <a href="{{ route('orders') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('orders*') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                        <span
                            class="material-symbols-outlined {{ request()->routeIs('orders*') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">receipt_long</span>
                        <span
                            class="{{ request()->routeIs('orders*') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Orders</span>
                    </a>

                    @role('admin')
                        <div class="mt-4 mb-2 px-3">
                            <span class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Admin</span>
                        </div>

                        <a href="{{ route('menus') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('menus') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                            <span
                                class="material-symbols-outlined {{ request()->routeIs('menus') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">restaurant_menu</span>
                            <span
                                class="{{ request()->routeIs('menus') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Menu
                                Items</span>
                        </a>

                        <a href="{{ route('categories') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('categories') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                            <span
                                class="material-symbols-outlined {{ request()->routeIs('categories') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">category</span>
                            <span
                                class="{{ request()->routeIs('categories') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Categories</span>
                        </a>

                        <a href="{{ route('tables') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('tables') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                            <span
                                class="material-symbols-outlined {{ request()->routeIs('tables') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">table_restaurant</span>
                            <span
                                class="{{ request()->routeIs('tables') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Tables</span>
                        </a>

                        <a href="{{ route('inventory') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('inventory') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                            <span
                                class="material-symbols-outlined {{ request()->routeIs('inventory') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">inventory_2</span>
                            <span
                                class="{{ request()->routeIs('inventory') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Inventory</span>
                        </a>

                        <a href="{{ route('reports') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('reports') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                            <span
                                class="material-symbols-outlined {{ request()->routeIs('reports') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">analytics</span>
                            <span
                                class="{{ request()->routeIs('reports') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Reports</span>
                        </a>

                        <a href="{{ route('users') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('users') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                            <span
                                class="material-symbols-outlined {{ request()->routeIs('users') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">group</span>
                            <span
                                class="{{ request()->routeIs('users') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Users</span>
                        </a>

                        <a href="{{ route('settings') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('settings') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all">
                            <span
                                class="material-symbols-outlined {{ request()->routeIs('settings') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">settings</span>
                            <span
                                class="{{ request()->routeIs('settings') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal">Settings</span>
                        </a>
                    @endrole
                </div>
            </div>

            <div class="mt-auto pt-6 flex flex-col gap-2">
                {{-- Logout --}}
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-500/20 border border-transparent hover:border-red-500/30 text-red-400 transition-all shadow-[inset_0_0_0_rgba(239,68,68,0)] hover:shadow-[inset_0_0_12px_rgba(239,68,68,0.2)]">
                            <span class="material-symbols-outlined">logout</span>
                            <span class="text-sm font-medium leading-normal">Log Out</span>
                        </button>
                    </form>
                </div>
        </nav>
