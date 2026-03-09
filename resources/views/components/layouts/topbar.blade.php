            {{-- Top Bar --}}
            <header
                class="flex items-center justify-between whitespace-nowrap border-b border-white/10 px-6 py-4 shrink-0 bg-black/10 backdrop-blur-md relative z-10">
                <div class="flex items-center gap-3">
                    <div
                        class="p-2 rounded-lg bg-primary/20 border border-primary/30 shadow-[inset_0_0_10px_rgba(212,115,17,0.3)]">
                        <span
                            class="material-symbols-outlined text-primary text-2xl drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">local_cafe</span>
                    </div>
                    <h2 class="text-white text-xl font-bold leading-tight tracking-[-0.015em] drop-shadow-md">Pos System
                    </h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-400 text-sm">{{ now()->format('D, d M Y • H:i') }}</span>
                </div>
            </header>
