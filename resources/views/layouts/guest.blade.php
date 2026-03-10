<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $title ?? 'Order — FlowServe' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>

<body
    class="font-display bg-gradient-to-br from-[#121118] via-[#1a1625] to-[#0a0a0f] text-gray-100 min-h-screen antialiased selection:bg-primary/30">
    <div
        class="relative flex flex-col min-h-screen w-full max-w-lg sm:max-w-2xl lg:max-w-5xl xl:max-w-6xl mx-auto lg:border-x lg:border-white/5">
        {{-- Ambient Orbs --}}
        <div class="fixed top-[-10%] left-[-10%] w-72 h-72 bg-primary/20 rounded-full blur-[100px] pointer-events-none">
        </div>
        <div
            class="fixed bottom-[-10%] right-[-10%] w-80 h-80 bg-purple-900/20 rounded-full blur-[120px] pointer-events-none">
        </div>

        {{-- Header --}}
        <header
            class="flex items-center justify-between border-b border-white/10 px-5 py-4 shrink-0 bg-black/10 backdrop-blur-md relative z-10">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 overflow-hidden rounded-lg">
                    <img src="{{ asset('img/logo/flow-serve.png') }}" alt="FlowServe Logo"
                        class="w-full h-full object-cover">
                </div>
                <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em] drop-shadow-md">FlowServe</h2>
            </div>
            @if (isset($tableNumber))
                <div
                    class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/20 border border-primary/30 shadow-[inset_0_0_8px_rgba(212,115,17,0.2)]">
                    <span class="material-symbols-outlined text-primary text-[16px]">table_restaurant</span>
                    <span class="text-primary text-sm font-bold">Meja #{{ $tableNumber }}</span>
                </div>
            @endif
        </header>

        {{-- Page Content --}}
        <main class="flex-1 relative z-10">
            {{ $slot }}
        </main>
    </div>

    <script
        src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @livewireScripts
</body>

</html>
