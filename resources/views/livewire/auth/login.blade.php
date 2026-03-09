<div>
    <h2 class="text-white text-xl font-bold text-center mb-2">Welcome Back</h2>
    <p class="text-gray-400 text-sm text-center mb-8">Sign in to your account</p>

    @if (session()->has('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit="login" class="flex flex-col gap-5">
        <div>
            <label class="text-gray-300 text-sm font-medium mb-1.5 block">Email</label>
            <div
                class="flex w-full items-stretch rounded-lg border border-white/10 bg-black/40 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] overflow-hidden focus-within:border-primary/50 focus-within:shadow-[inset_0_0_10px_rgba(212,115,17,0.2)] transition-all">
                <div class="text-gray-400 flex items-center justify-center pl-3">
                    <span class="material-symbols-outlined text-[20px]">mail</span>
                </div>
                <input wire:model="email" type="email"
                    class="form-input flex w-full min-w-0 flex-1 resize-none rounded-lg text-white focus:outline-none focus:ring-0 border-none bg-transparent h-11 placeholder:text-gray-500 px-3 text-sm"
                    placeholder="admin@flowserve.com" />
            </div>
            @error('email')
                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="text-gray-300 text-sm font-medium mb-1.5 block">Password</label>
            <div
                class="flex w-full items-stretch rounded-lg border border-white/10 bg-black/40 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] overflow-hidden focus-within:border-primary/50 focus-within:shadow-[inset_0_0_10px_rgba(212,115,17,0.2)] transition-all">
                <div class="text-gray-400 flex items-center justify-center pl-3">
                    <span class="material-symbols-outlined text-[20px]">lock</span>
                </div>
                <input wire:model="password" type="password"
                    class="form-input flex w-full min-w-0 flex-1 resize-none rounded-lg text-white focus:outline-none focus:ring-0 border-none bg-transparent h-11 placeholder:text-gray-500 px-3 text-sm"
                    placeholder="Enter your password" />
            </div>
            @error('password')
                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="remember" type="checkbox"
                    class="form-checkbox size-4 rounded bg-black/40 border border-white/10 text-primary focus:ring-primary/30" />
                <span class="text-gray-400 text-sm">Remember me</span>
            </label>
        </div>

        <button type="submit"
            class="w-full h-12 rounded-xl bg-primary border border-primary/50 text-white text-base font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.3),0_0_24px_rgba(212,115,17,0.5)] flex items-center justify-center gap-2 group">
            <span wire:loading.remove wire:target="login">Sign In</span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                <svg class="animate-spin size-5" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Signing in...
            </span>
            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform" wire:loading.remove
                wire:target="login">arrow_forward</span>
        </button>
    </form>
</div>
