<div class="mb-4 px-3">
    <button wire:click="toggle"
        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl border transition-all shadow-md
        {{ $isOpen
            ? 'bg-emerald-500/10 border-emerald-500/30 hover:bg-emerald-500/20 shadow-[inset_0_0_8px_rgba(16,185,129,0.1)]'
            : 'bg-red-500/10 border-red-500/30 hover:bg-red-500/20 shadow-[inset_0_0_8px_rgba(239,68,68,0.1)]' }}">

        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined {{ $isOpen ? 'text-emerald-400' : 'text-red-400' }}">
                {{ $isOpen ? 'storefront' : 'storefront' }}
            </span>
            <span class="text-sm font-bold {{ $isOpen ? 'text-emerald-400' : 'text-red-400' }}">
                {{ $isOpen ? 'Store Open' : 'Store Closed' }}
            </span>
        </div>

        {{-- Toggle Switch --}}
        <div
            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none">
            <span aria-hidden="true"
                class="pointer-events-none absolute h-full w-full rounded-full transition-colors ease-in-out duration-200 
                {{ $isOpen ? 'bg-emerald-500' : 'bg-gray-600' }}">
            </span>
            <span aria-hidden="true"
                class="pointer-events-none absolute left-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition-transform ease-in-out duration-200 
                {{ $isOpen ? 'translate-x-4' : 'translate-x-0' }}">
            </span>
        </div>
    </button>
</div>
