            {{-- Top Bar --}}
            <header
                class="flex items-center justify-between whitespace-nowrap border-b border-white/10 px-6 py-4 shrink-0 bg-black/10 backdrop-blur-md relative z-10">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 overflow-hidden rounded-lg">
                        <img src="{{ asset('img/logo/flow-serve.png') }}" alt="FlowServe Logo"
                            class="w-full h-full object-cover">
                    </div>
                    <h2 class="text-white text-xl font-bold leading-tight tracking-[-0.015em] drop-shadow-md">FlowServe
                    </h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-400 text-sm" id="topbar-clock">{{ now()->format('D, d M Y • H:i:s') }}</span>
                </div>
            </header>

            @push('scripts')
                <script>
                    function updateTopbarClock() {
                        const el = document.getElementById('topbar-clock');
                        if (el) {
                            const now = new Date();

                            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                            const dayName = days[now.getDay()];
                            const day = String(now.getDate()).padStart(2, '0');
                            const month = months[now.getMonth()];
                            const year = now.getFullYear();

                            const dateStr = `${dayName}, ${day} ${month} ${year}`;

                            const timeStr = now.toLocaleTimeString('id-ID', {
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            });

                            el.textContent = dateStr + ' • ' + timeStr;
                        }
                    }
                    setInterval(updateTopbarClock, 1000);
                    updateTopbarClock();
                </script>
            @endpush
