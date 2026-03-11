        {{-- Sidebar --}}
        <nav x-data="{ collapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
            x-init="$watch('collapsed', val => localStorage.setItem('sidebarCollapsed', val))"
            :class="collapsed ? 'w-16' : 'w-[240px]'"
            class="flex h-full flex-col justify-between border-r border-white/10 bg-black/20 backdrop-blur-xl p-4 shrink-0 shadow-[4px_0_24px_-16px_rgba(0,0,0,0.5)] z-20 transition-all duration-300 overflow-y-auto no-scrollbar">
            <div class="flex flex-col gap-8">
                {{-- User Profile --}}
                <div class="flex gap-3 items-center">
                    <div
                        class="flex items-center justify-center rounded-full size-10 bg-primary/20 border border-primary/30 text-primary font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex flex-col overflow-hidden whitespace-nowrap" x-show="!collapsed" x-cloak
                        x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100">
                        <h1 class="text-white text-base font-medium leading-normal">{{ auth()->user()->name }}</h1>
                        <p class="text-primary text-sm font-normal leading-normal drop-shadow-sm">
                            {{ auth()->user()->roles->first()?->name ?? 'Staff' }}
                        </p>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="flex flex-col gap-2">
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                        :class="collapsed && 'justify-center !px-0'">
                        <span
                            class="material-symbols-outlined shrink-0 {{ request()->routeIs('dashboard') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">dashboard</span>
                        <span x-show="!collapsed" x-cloak
                            class="{{ request()->routeIs('dashboard') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Dashboard</span>
                    </a>

                    <a href="{{ route('pos') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('pos') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                        :class="collapsed && 'justify-center !px-0'">
                        <span
                            class="material-symbols-outlined shrink-0 {{ request()->routeIs('pos') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">point_of_sale</span>
                        <span x-show="!collapsed" x-cloak
                            class="{{ request()->routeIs('pos') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">POS</span>
                    </a>

                    <a href="{{ route('kitchen') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kitchen') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                        :class="collapsed && 'justify-center !px-0'">
                        <span
                            class="material-symbols-outlined shrink-0 {{ request()->routeIs('kitchen') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">soup_kitchen</span>
                        <span x-show="!collapsed" x-cloak
                            class="{{ request()->routeIs('kitchen') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Kitchen</span>
                    </a>

                    <a href="{{ route('orders') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('orders*') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                        :class="collapsed && 'justify-center !px-0'">
                        <span
                            class="material-symbols-outlined shrink-0 {{ request()->routeIs('orders*') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">receipt_long</span>
                        <span x-show="!collapsed" x-cloak
                            class="{{ request()->routeIs('orders*') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Orders</span>
                    </a>

                    @role('admin')
                        <div class="mt-4 mb-2 px-3" x-show="!collapsed" x-cloak>
                            <span class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Admin</span>
                        </div>
                        <div class="mt-2 mb-0" x-show="collapsed" x-cloak>
                            <div class="border-t border-white/10"></div>
                        </div>

                        <a href="{{ route('menus') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('menus') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                            :class="collapsed && 'justify-center !px-0'">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->routeIs('menus') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">restaurant_menu</span>
                            <span x-show="!collapsed" x-cloak
                                class="{{ request()->routeIs('menus') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Menu
                                Items</span>
                        </a>

                        <a href="{{ route('categories') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('categories') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                            :class="collapsed && 'justify-center !px-0'">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->routeIs('categories') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">category</span>
                            <span x-show="!collapsed" x-cloak
                                class="{{ request()->routeIs('categories') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Categories</span>
                        </a>

                        <a href="{{ route('tables') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('tables') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                            :class="collapsed && 'justify-center !px-0'">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->routeIs('tables') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">table_restaurant</span>
                            <span x-show="!collapsed" x-cloak
                                class="{{ request()->routeIs('tables') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Tables</span>
                        </a>

                        <a href="{{ route('inventory') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('inventory') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                            :class="collapsed && 'justify-center !px-0'">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->routeIs('inventory') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">inventory_2</span>
                            <span x-show="!collapsed" x-cloak
                                class="{{ request()->routeIs('inventory') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Inventory</span>
                        </a>

                        <a href="{{ route('reports') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('reports') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                            :class="collapsed && 'justify-center !px-0'">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->routeIs('reports') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">analytics</span>
                            <span x-show="!collapsed" x-cloak
                                class="{{ request()->routeIs('reports') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Reports</span>
                        </a>

                        <a href="{{ route('users') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('users') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                            :class="collapsed && 'justify-center !px-0'">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->routeIs('users') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">group</span>
                            <span x-show="!collapsed" x-cloak
                                class="{{ request()->routeIs('users') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Users</span>
                        </a>

                        <a href="{{ route('settings') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('settings') ? 'bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)]' : 'hover:bg-white/10 border border-transparent hover:border-white/5' }} transition-all"
                            :class="collapsed && 'justify-center !px-0'">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->routeIs('settings') ? 'text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]' : 'text-gray-400' }}">settings</span>
                            <span x-show="!collapsed" x-cloak
                                class="{{ request()->routeIs('settings') ? 'text-primary drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]' : 'text-gray-200' }} text-sm font-medium leading-normal whitespace-nowrap">Settings</span>
                        </a>
                    @endrole
                </div>
            </div>

            <div class="mt-auto pt-6 flex flex-col gap-2">
                {{-- Collapse Toggle --}}
                <button @click="collapsed = !collapsed"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent hover:border-white/5 text-gray-400 transition-all"
                    :class="collapsed && 'justify-center !px-0'">
                    <span class="material-symbols-outlined shrink-0 transition-transform duration-300"
                        :class="collapsed ? 'rotate-180' : ''">chevron_left</span>
                    <span x-show="!collapsed" x-cloak
                        class="text-sm font-medium leading-normal whitespace-nowrap">Collapse</span>
                </button>

                {{-- Logout --}}
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-500/20 border border-transparent hover:border-red-500/30 text-red-400 transition-all shadow-[inset_0_0_0_rgba(239,68,68,0)] hover:shadow-[inset_0_0_12px_rgba(239,68,68,0.2)]"
                            :class="collapsed && 'justify-center !px-0'">
                            <span class="material-symbols-outlined shrink-0">logout</span>
                            <span x-show="!collapsed" x-cloak
                                class="text-sm font-medium leading-normal whitespace-nowrap">Log Out</span>
                        </button>
                    </form>
                </div>
        </nav>
