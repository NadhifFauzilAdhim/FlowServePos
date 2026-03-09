<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $title ?? 'FlowServe — Login' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>

<body
    class="font-display bg-gradient-to-br from-[#121118] via-[#1a1625] to-[#0a0a0f] text-gray-100 min-h-screen antialiased selection:bg-primary/30 flex items-center justify-center">
    {{-- Ambient Orbs --}}
    <div class="fixed top-[-10%] left-[-10%] w-96 h-96 bg-primary/20 rounded-full blur-[120px] pointer-events-none">
    </div>
    <div
        class="fixed bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-purple-900/20 rounded-full blur-[150px] pointer-events-none">
    </div>

    <div class="relative z-10 w-full max-w-md px-6">
        {{-- Logo --}}
        <div class="flex items-center justify-center gap-3 mb-8">
            <div class="h-10 w-10 overflow-hidden rounded-xl">
                <img src="{{ asset('img/logo/flow-serve.png') }}" alt="FlowServe Logo"
                    class="w-full h-full object-cover">
            </div>
            <h1 class="text-white text-2xl font-bold tracking-[-0.015em] drop-shadow-md">FlowServe</h1>
        </div>

        {{-- Card --}}
        <div class="bg-black/30 backdrop-blur-2xl border border-white/10 rounded-2xl p-8 shadow-2xl">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>

</html>
