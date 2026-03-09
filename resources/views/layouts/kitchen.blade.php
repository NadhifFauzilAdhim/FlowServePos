<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kitchen Display — FlowServe</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>

<body
    class="font-display bg-gradient-to-br from-[#121118] via-[#1a1625] to-[#0a0a0f] text-gray-100 min-h-screen antialiased selection:bg-primary/30">
    <div class="flex flex-col h-screen w-full overflow-hidden relative">
        {{-- Ambient Orbs --}}
        <div
            class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary/15 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-[-10%] right-[10%] w-[500px] h-[500px] bg-purple-900/15 rounded-full blur-[150px] pointer-events-none">
        </div>

        {{-- Header --}}
        <header
            class="flex items-center justify-between px-6 py-3 border-b border-white/10 bg-black/20 backdrop-blur-md shrink-0 relative z-10">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 overflow-hidden rounded-lg">
                    <img src="{{ asset('img/logo/flow-serve.png') }}" alt="FlowServe Logo"
                        class="w-full h-full object-cover">
                </div>
                <div>
                    <h1 class="text-white text-xl font-bold leading-tight">Kitchen Display</h1>
                    <p class="text-gray-500 text-xs">FlowServe</p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-gray-400 text-sm font-medium" id="kds-clock"></div>
                <a href="{{ route('dashboard') }}" wire:navigate
                    class="px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm hover:bg-white/10 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back
                </a>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-hidden relative z-10">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts

    <script>
        function updateClock() {
            const el = document.getElementById('kds-clock');
            if (el) {
                el.textContent = new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>

</html>
