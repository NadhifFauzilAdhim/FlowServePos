<div class="p-6">
    <div class="mb-8">
        <h1 class="text-white text-2xl font-bold drop-shadow-md">User Management</h1>
        <p class="text-gray-400 text-sm mt-1">Create and manage user accounts</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Create User Form --}}
        <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-6">
            <h3 class="text-white text-lg font-bold mb-5">Create New User</h3>
            <form wire:submit="createUser" class="flex flex-col gap-4">
                <div>
                    <label class="text-gray-300 text-sm font-medium mb-1.5 block">Full Name</label>
                    <input wire:model="name" type="text"
                        class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm placeholder:text-gray-500 focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                        placeholder="John Doe" />
                    @error('name')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-gray-300 text-sm font-medium mb-1.5 block">Email</label>
                    <input wire:model="email" type="email"
                        class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm placeholder:text-gray-500 focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                        placeholder="user@lumina.cafe" />
                    @error('email')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-gray-300 text-sm font-medium mb-1.5 block">Password</label>
                    <input wire:model="password" type="password"
                        class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm placeholder:text-gray-500 focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                        placeholder="Min 6 characters" />
                    @error('password')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-gray-300 text-sm font-medium mb-1.5 block">Confirm Password</label>
                    <input wire:model="password_confirmation" type="password"
                        class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm placeholder:text-gray-500 focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]"
                        placeholder="Repeat password" />
                </div>
                <div>
                    <label class="text-gray-300 text-sm font-medium mb-1.5 block">Role</label>
                    <select wire:model="role"
                        class="w-full rounded-lg border border-white/10 bg-black/40 text-white h-10 px-3 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]">
                        <option value="cashier">Cashier</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit"
                    class="w-full h-11 rounded-xl bg-primary border border-primary/50 text-white font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)] mt-2">
                    <span wire:loading.remove wire:target="createUser">Create User</span>
                    <span wire:loading wire:target="createUser">Creating...</span>
                </button>
            </form>
        </div>

        {{-- Users Table --}}
        <div class="lg:col-span-2 bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-6">
            <h3 class="text-white text-lg font-bold mb-5">All Users</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th
                                class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-3 px-4">
                                Name</th>
                            <th
                                class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-3 px-4">
                                Email</th>
                            <th
                                class="text-left text-gray-400 text-xs font-semibold uppercase tracking-wider py-3 px-4">
                                Role</th>
                            <th
                                class="text-right text-gray-400 text-xs font-semibold uppercase tracking-wider py-3 px-4">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-white text-sm font-medium">{{ $user->name }}</td>
                                <td class="py-3 px-4 text-gray-300 text-sm">{{ $user->email }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        {{ $user->hasRole('admin') ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30' }}">
                                        {{ ucfirst($user->roles->first()?->name ?? 'No Role') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if ($user->id !== auth()->id())
                                        <button wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="Are you sure you want to delete this user?"
                                            class="text-red-400 hover:text-red-300 text-sm transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
