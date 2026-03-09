<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $title ?? 'FlowServe POS' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>

<body
    class="font-display bg-gradient-to-br from-[#121118] via-[#1a1625] to-[#0a0a0f] text-gray-100 min-h-screen antialiased selection:bg-primary/30">
    <div class="relative flex h-screen w-full flex-row overflow-hidden">
        <x-layouts.sidebar />

        {{-- Main Content --}}
        <main class="flex flex-1 flex-col h-full overflow-hidden bg-transparent relative">
            {{-- Ambient Orbs --}}
            <div
                class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary/20 rounded-full blur-[120px] pointer-events-none">
            </div>
            <div
                class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-purple-900/20 rounded-full blur-[150px] pointer-events-none">
            </div>

            <x-layouts.topbar />

            {{-- Page Content --}}
            <div class="flex-1 overflow-y-auto relative z-10">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
