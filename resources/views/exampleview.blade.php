<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>POS Order Interface (Dark Glassmorphism)</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#d47311",
                    },
                    fontFamily: {
                        "display": ["Manrope"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&amp;display=swap" rel="stylesheet" />
</head>

<body
    class="font-display bg-gradient-to-br from-[#121118] via-[#1a1625] to-[#0a0a0f] text-gray-100 min-h-screen antialiased selection:bg-primary/30">
    <div class="relative flex h-screen w-full flex-row overflow-hidden">
        <nav
            class="flex h-full w-[240px] flex-col justify-between border-r border-white/10 bg-black/20 backdrop-blur-xl p-4 shrink-0 shadow-[4px_0_24px_-16px_rgba(0,0,0,0.5)] z-20">
            <div class="flex flex-col gap-8">
                <div class="flex gap-3 items-center">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border border-white/20 shadow-[inset_0_0_10px_rgba(255,255,255,0.1)]"
                        data-alt="Profile picture of Cashier 1"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD8q_Oue1D2lQ849cgQr0PfyGUNSVO3PDln-3jJJg4f0NsfQtrp0XzLvm_W9DBg0uqIoSL26VGxg3z47yiM9g387IZDcUVr3XeWFvIjMWhW-Nf_CFQwWW8oo9aK-47I8JjCif6S0r_vuoTVUQf7I7M1_z8BXCsOC0j92UEqK9UgfeZa_woS5lxHbqB_TQBntTCmuBAKf-k_pNkkLa7Wa6lg4jKEKVdp827dlJmCDOGzNv9nhbpo0MPp3GnW7gTmM0Ayr_o8K6UYmqw");'>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-white text-base font-medium leading-normal">Alex Chen</h1>
                        <p class="text-primary text-sm font-normal leading-normal shadow-primary/50 drop-shadow-sm">
                            Active</p>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent hover:border-white/5 transition-all"
                        href="#">
                        <span class="material-symbols-outlined text-gray-400">home</span>
                        <span class="text-gray-200 text-sm font-medium leading-normal">Dashboard</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 border border-primary/30 shadow-[inset_0_0_12px_rgba(212,115,17,0.2)] transition-all"
                        href="#">
                        <span
                            class="material-symbols-outlined text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">coffee</span>
                        <span
                            class="text-primary text-sm font-medium leading-normal drop-shadow-[0_0_4px_rgba(212,115,17,0.3)]">Menu</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent hover:border-white/5 transition-all"
                        href="#">
                        <span class="material-symbols-outlined text-gray-400">receipt_long</span>
                        <span class="text-gray-200 text-sm font-medium leading-normal">Orders</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent hover:border-white/5 transition-all"
                        href="#">
                        <span class="material-symbols-outlined text-gray-400">group</span>
                        <span class="text-gray-200 text-sm font-medium leading-normal">Customers</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent hover:border-white/5 transition-all"
                        href="#">
                        <span class="material-symbols-outlined text-gray-400">settings</span>
                        <span class="text-gray-200 text-sm font-medium leading-normal">Settings</span>
                    </a>
                </div>
            </div>
            <div>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-500/20 border border-transparent hover:border-red-500/30 text-red-400 transition-all shadow-[inset_0_0_0_rgba(239,68,68,0)] hover:shadow-[inset_0_0_12px_rgba(239,68,68,0.2)]"
                    href="#">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-sm font-medium leading-normal">Log Out</span>
                </a>
            </div>
        </nav>
        <main class="flex flex-1 flex-col h-full overflow-hidden bg-transparent relative">
            <div
                class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary/20 rounded-full blur-[120px] pointer-events-none">
            </div>
            <div
                class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-purple-900/20 rounded-full blur-[150px] pointer-events-none">
            </div>
            <header
                class="flex items-center justify-between whitespace-nowrap border-b border-white/10 px-6 py-4 shrink-0 bg-black/10 backdrop-blur-md relative z-10">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 rounded-lg bg-primary/20 border border-primary/30 shadow-[inset_0_0_10px_rgba(212,115,17,0.3)]">
                            <span
                                class="material-symbols-outlined text-primary text-2xl drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">local_cafe</span>
                        </div>
                        <h2 class="text-white text-xl font-bold leading-tight tracking-[-0.015em] drop-shadow-md">Lumina
                            Café</h2>
                    </div>
                    <label class="flex flex-col min-w-64 !h-10 max-w-md hidden md:flex">
                        <div
                            class="flex w-full flex-1 items-stretch rounded-lg h-full border border-white/10 bg-black/40 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] overflow-hidden focus-within:border-primary/50 focus-within:shadow-[inset_0_0_10px_rgba(212,115,17,0.2)] transition-all">
                            <div class="text-gray-400 flex items-center justify-center pl-3">
                                <span class="material-symbols-outlined">search</span>
                            </div>
                            <input
                                class="form-input flex w-full min-w-0 flex-1 resize-none rounded-lg text-white focus:outline-none focus:ring-0 border-none bg-transparent h-full placeholder:text-gray-500 px-3 text-sm font-normal leading-normal"
                                placeholder="Search menu items (e.g., 'Latte')" value="" />
                        </div>
                    </label>
                </div>
                <div class="flex items-center gap-4">
                    <button
                        class="flex items-center justify-center rounded-lg size-10 text-gray-300 bg-white/5 border border-white/10 hover:bg-white/10 shadow-[inset_0_0_8px_rgba(255,255,255,0.05)] transition-all relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span
                            class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border border-red-800 shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                    </button>
                </div>
            </header>
            <div class="flex flex-1 overflow-hidden relative z-10">
                <div class="flex-1 flex flex-col h-full overflow-hidden">
                    <div
                        class="flex gap-3 p-6 overflow-x-auto no-scrollbar shrink-0 border-b border-white/10 bg-black/5 backdrop-blur-sm">
                        <button
                            class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-primary border border-primary/50 px-5 text-white text-sm font-medium leading-normal shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)]">All
                            Items</button>
                        <button
                            class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-white/5 border border-white/10 px-5 text-gray-300 text-sm font-medium leading-normal hover:bg-white/10 hover:border-white/20 shadow-[inset_0_0_8px_rgba(255,255,255,0.05)] transition-all">Hot
                            Coffee</button>
                        <button
                            class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-white/5 border border-white/10 px-5 text-gray-300 text-sm font-medium leading-normal hover:bg-white/10 hover:border-white/20 shadow-[inset_0_0_8px_rgba(255,255,255,0.05)] transition-all">Cold
                            Drinks</button>
                        <button
                            class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-white/5 border border-white/10 px-5 text-gray-300 text-sm font-medium leading-normal hover:bg-white/10 hover:border-white/20 shadow-[inset_0_0_8px_rgba(255,255,255,0.05)] transition-all">Pastries</button>
                        <button
                            class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-white/5 border border-white/10 px-5 text-gray-300 text-sm font-medium leading-normal hover:bg-white/10 hover:border-white/20 shadow-[inset_0_0_8px_rgba(255,255,255,0.05)] transition-all">Sandwiches</button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="grid grid-cols-[repeat(auto-fill,minmax(180px,1fr))] gap-6">
                            <div
                                class="flex flex-col group cursor-pointer bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-3 hover:bg-black/40 hover:border-primary/50 transition-all shadow-lg">
                                <div
                                    class="w-full aspect-square rounded-xl overflow-hidden mb-3 relative shadow-[inset_0_0_20px_rgba(0,0,0,0.5)]">
                                    <div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-500"
                                        data-alt="Image of Espresso"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB7R716WTFg5GBk58u0qpExqup71bNrwQYznKDJLOuBIgdhl7wXG9P8DqBbYzLLt2pg6ZGj8GD8p2JrMM72i8EcDe5-HuYfRz68GHF8RBysi1gkcILwS983Gv7oyNHSCq0KchobWIlMZNKp6-v3zDvQkGXi97Dapmv_GZoHtuNfRUBL_wj7ndtfql4z9eleUSFfy8TjI1U7GoDlChCfZulTL6kLJA_SDQyapRGYYVp2dv4y6FR6nLHiSK2Cg_-HxQQ5HDV9AmdHETA");'>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-50 group-hover:opacity-20 transition-opacity">
                                    </div>
                                </div>
                                <div class="px-1">
                                    <h3 class="text-white text-base font-semibold leading-tight mb-1">Espresso</h3>
                                    <p class="text-gray-400 text-sm font-normal line-clamp-1 mb-3">Double shot of rich
                                        espresso</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-white font-bold text-lg drop-shadow-md">$3.00</span>
                                        <button
                                            class="size-8 rounded-full bg-primary/20 border border-primary/30 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white group-hover:shadow-[inset_0_0_10px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.5)] transition-all">
                                            <span class="material-symbols-outlined text-sm drop-shadow-sm">add</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex flex-col group cursor-pointer bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-3 hover:bg-black/40 hover:border-primary/50 transition-all shadow-lg">
                                <div
                                    class="w-full aspect-square rounded-xl overflow-hidden mb-3 relative shadow-[inset_0_0_20px_rgba(0,0,0,0.5)]">
                                    <div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-500"
                                        data-alt="Image of Cappuccino"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBWjMTER3PTUYVtdsS83vjwDVfj4dddVoeB0zEnFJKXesABaLcsLjbsl1F869RPeKbU04-t8K4v-2VC4V9fjHY5zGXjYmrWUUP35slqXQiIs8kciPBB7KxORHhCWe9skYS7REoZXyQXio-OV9tDRFDCNBfH91KOclcQPBSP7diE84RBpGVh7ePO0mi1gef11Ywq-EcY7eef2zlwzqqd-YYzqAcO3B-JMY5fehItVUzQ9yc39tv5vbdF3WSmqLWePBrbQOIVORevjIs");'>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-50 group-hover:opacity-20 transition-opacity">
                                    </div>
                                </div>
                                <div class="px-1">
                                    <h3 class="text-white text-base font-semibold leading-tight mb-1">Cappuccino</h3>
                                    <p class="text-gray-400 text-sm font-normal line-clamp-1 mb-3">Espresso with steamed
                                        milk foam</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-white font-bold text-lg drop-shadow-md">$4.50</span>
                                        <button
                                            class="size-8 rounded-full bg-primary/20 border border-primary/30 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white group-hover:shadow-[inset_0_0_10px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.5)] transition-all">
                                            <span class="material-symbols-outlined text-sm drop-shadow-sm">add</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex flex-col group cursor-pointer bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-3 hover:bg-black/40 hover:border-primary/50 transition-all shadow-lg">
                                <div
                                    class="w-full aspect-square rounded-xl overflow-hidden mb-3 relative shadow-[inset_0_0_20px_rgba(0,0,0,0.5)]">
                                    <div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-500"
                                        data-alt="Image of Iced Latte"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBPMcN1B4VEWrf-J4kkXAVxT97B997Vj_ccCH-CXUNPkmH6_SAeMHWd55LXV0qKPrs87o58nYqUxXSawjwC2Tlse5aGkLAnC8TdkYeCIE6-EepLYMHPU7gxkz4Na_gjLlj-IOwdOzKT03jZgz5wWgHzgEvbFu2Nm3TGPK5vjDd6mQ2sbH1hOOXe-NXUR2PK-QO5q6zCywfaoZR8wZYL68TW0v2bD1W79JoXlE8Mbwo2TLKJqtn7r6JjC2g3uii-doCrej2acIJ2iOw");'>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-50 group-hover:opacity-20 transition-opacity">
                                    </div>
                                </div>
                                <div class="px-1">
                                    <h3 class="text-white text-base font-semibold leading-tight mb-1">Iced Latte</h3>
                                    <p class="text-gray-400 text-sm font-normal line-clamp-1 mb-3">Chilled espresso
                                        with milk over ice</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-white font-bold text-lg drop-shadow-md">$4.75</span>
                                        <button
                                            class="size-8 rounded-full bg-primary/20 border border-primary/30 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white group-hover:shadow-[inset_0_0_10px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.5)] transition-all">
                                            <span class="material-symbols-outlined text-sm drop-shadow-sm">add</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex flex-col group cursor-pointer bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-3 hover:bg-black/40 hover:border-primary/50 transition-all shadow-lg">
                                <div
                                    class="w-full aspect-square rounded-xl overflow-hidden mb-3 relative shadow-[inset_0_0_20px_rgba(0,0,0,0.5)]">
                                    <div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-500"
                                        data-alt="Image of Cold Brew"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAv1NDwdTvlR4888AV8MMXOwpq4Tu16K80sO1fdgNcrtn2ApeRBl1HMTjWS0xu9wZ_WZrq8wP7i60ouATrp--_TGPqxCD1LzIBoMqFuVJ_uKxXl8m7Ib73-nRd1Ss4itJtHPc7dq-OozL-FTjOsLH_9GFzsE5jsq6MHSjvJt1iRSC8LehJnJw8pH7_KrjpwDrFWOiknpHuAbsAC6KDStIISfOieUAqEO-HB827SxTfli3kbPG2YvA68gQyChIKFuTegel4DmD-2ZIQ");'>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-50 group-hover:opacity-20 transition-opacity">
                                    </div>
                                </div>
                                <div class="px-1">
                                    <h3 class="text-white text-base font-semibold leading-tight mb-1">Cold Brew</h3>
                                    <p class="text-gray-400 text-sm font-normal line-clamp-1 mb-3">12-hour steeped cold
                                        coffee</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-white font-bold text-lg drop-shadow-md">$4.00</span>
                                        <button
                                            class="size-8 rounded-full bg-primary/20 border border-primary/30 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white group-hover:shadow-[inset_0_0_10px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.5)] transition-all">
                                            <span class="material-symbols-outlined text-sm drop-shadow-sm">add</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex flex-col group cursor-pointer bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-3 hover:bg-black/40 hover:border-primary/50 transition-all shadow-lg">
                                <div
                                    class="w-full aspect-square rounded-xl overflow-hidden mb-3 relative shadow-[inset_0_0_20px_rgba(0,0,0,0.5)]">
                                    <div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-500"
                                        data-alt="Image of Croissant"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAdnztZZcLuhuERpILuO-tmrYYPtDpT14TAjgm4TY-iyDbLAcBlxgbe2e7XqlMCW40KD6yJqZQnrelOxaApt-8J8ndY0TrdsrBxvfTYGC64jcaGzDPbw2bUFd0ibvc-A1Ne3yKQUJtytF171wCehkZy4LmuAhIHenP2ZuOPFQ6pGF_LvSZKbwpFKcGMOu-aIYKgQvYTa_pmMpRwDF-d93zUBS9eS1lORO39mRF4mE4l1_kU1xjbPOCx5qR9U7P_q1EtBpIvSoXhPaE");'>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-50 group-hover:opacity-20 transition-opacity">
                                    </div>
                                </div>
                                <div class="px-1">
                                    <h3 class="text-white text-base font-semibold leading-tight mb-1">Butter Croissant
                                    </h3>
                                    <p class="text-gray-400 text-sm font-normal line-clamp-1 mb-3">Flaky, buttery
                                        French pastry</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-white font-bold text-lg drop-shadow-md">$3.50</span>
                                        <button
                                            class="size-8 rounded-full bg-primary/20 border border-primary/30 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white group-hover:shadow-[inset_0_0_10px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.5)] transition-all">
                                            <span class="material-symbols-outlined text-sm drop-shadow-sm">add</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex flex-col group cursor-pointer bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-3 hover:bg-black/40 hover:border-primary/50 transition-all shadow-lg">
                                <div
                                    class="w-full aspect-square rounded-xl overflow-hidden mb-3 relative shadow-[inset_0_0_20px_rgba(0,0,0,0.5)]">
                                    <div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-500"
                                        data-alt="Image of Avocado Toast"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD4acNuYPqJqmukSR_HC8sq3wTh15mDIRqCvXShcd_X6JbeijDCN3vZcTPYC0jnWC-PE_ZWGaNA1LYJLOLCUKclrGs9kfPJibqiSshHtO39Vmn1n5HpRidH1OX1UxQ_efQE1-Iy9_E0sD4K0Po-9HYMU0XgjYi8ml95OSpM6r1f9rlw8UmrWkg1s5AUUAg_-I9iSlxn885k_X24RD_MszrQzyssoYl42EY5xqQNxyrvZP3W3EcEHlfNYEO-lHdOwwVryyyLrDxvbdg");'>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-50 group-hover:opacity-20 transition-opacity">
                                    </div>
                                </div>
                                <div class="px-1">
                                    <h3 class="text-white text-base font-semibold leading-tight mb-1">Avocado Toast
                                    </h3>
                                    <p class="text-gray-400 text-sm font-normal line-clamp-1 mb-3">Sourdough with
                                        smashed avocado</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-white font-bold text-lg drop-shadow-md">$8.50</span>
                                        <button
                                            class="size-8 rounded-full bg-primary/20 border border-primary/30 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white group-hover:shadow-[inset_0_0_10px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.5)] transition-all">
                                            <span class="material-symbols-outlined text-sm drop-shadow-sm">add</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="w-[360px] flex flex-col h-full bg-black/20 backdrop-blur-2xl border-l border-white/10 shrink-0 shadow-[-8px_0_32px_-16px_rgba(0,0,0,0.8)] z-20">
                    <div class="p-5 border-b border-white/10 shrink-0 bg-black/10">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-white text-xl font-bold leading-tight drop-shadow-md">Current Order</h3>
                            <span
                                class="bg-primary/20 border border-primary/30 text-primary px-3 py-1 rounded-full text-xs font-bold shadow-[inset_0_0_8px_rgba(212,115,17,0.2)]">#2048</span>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="flex-1 py-2.5 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm font-medium hover:bg-white/10 transition-all shadow-[inset_0_0_8px_rgba(255,255,255,0.05)]">Dine
                                In</button>
                            <button
                                class="flex-1 py-2.5 rounded-lg bg-primary border border-primary/50 text-white text-sm font-medium shadow-[inset_0_0_12px_rgba(255,255,255,0.3),0_0_15px_rgba(212,115,17,0.4)] transition-all">Takeout</button>
                            <button
                                class="flex-1 py-2.5 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm font-medium hover:bg-white/10 transition-all shadow-[inset_0_0_8px_rgba(255,255,255,0.05)]">Delivery</button>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto p-5 flex flex-col gap-4">
                        <div class="flex gap-4 pb-4 border-b border-white/10">
                            <div class="w-16 h-16 rounded-xl bg-center bg-no-repeat bg-cover border border-white/10 shrink-0 shadow-[inset_0_0_10px_rgba(0,0,0,0.5)]"
                                data-alt="Thumbnail of Cappuccino"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCfhd48RNXCGhpDzRE5VomnMz54L2d2W09Ja-EBSOf1ux5Qh5Jauc5QuK3JYBPb9E7ggVaU9u8EI6e2kxvEwq-0BAwqwNWeqyb_RHlJlpsFubYgxzhWrdK8YYq5MHjfrHFn8skUK7sMb-9ROxqdKhoKFM04VFOG-GJ-jHzhoNlrL-wiCofiNXk6OeaQ4-JVPDEJ2OJkaepdepPV0yz5mDcVC785LWeAiuS-jg__DIxpUNALJScYRxYsHLYV34oQUZhi5bieWNdhtAU");'>
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-white font-semibold text-sm drop-shadow-sm">Cappuccino</h4>
                                        <p class="text-gray-400 text-xs mt-0.5">Oat milk, Extra hot</p>
                                    </div>
                                    <span class="text-white font-bold text-sm">$5.50</span>
                                </div>
                                <div class="flex items-center gap-3 mt-2">
                                    <button
                                        class="size-7 rounded-full bg-white/5 border border-white/10 text-gray-300 flex items-center justify-center hover:bg-white/10 shadow-[inset_0_0_6px_rgba(255,255,255,0.05)] transition-all">
                                        <span class="material-symbols-outlined text-[16px]">remove</span>
                                    </button>
                                    <span class="text-white text-sm font-bold w-4 text-center">1</span>
                                    <button
                                        class="size-7 rounded-full bg-white/5 border border-white/10 text-gray-300 flex items-center justify-center hover:bg-white/10 shadow-[inset_0_0_6px_rgba(255,255,255,0.05)] transition-all">
                                        <span class="material-symbols-outlined text-[16px]">add</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 pb-4 border-b border-white/10">
                            <div class="w-16 h-16 rounded-xl bg-center bg-no-repeat bg-cover border border-white/10 shrink-0 shadow-[inset_0_0_10px_rgba(0,0,0,0.5)]"
                                data-alt="Thumbnail of Avocado Toast"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB7ClmhxeaUqRLx1EtgRdEE7xl51AcLQEZNPlIs2xBWrBLNrpyLJiLxlw9gGuLDo8q1_ugPY9yjhxXvabEreg_qOolia-Upjf_qISfhUZwzej6aBYduVoAddeqVGS4Nb6-EPP5d01IS8CZXl8Z4rrBnzIvbcdllZ7Vmq4_JMyARVZMm76JU6_emWbAKnU2AGKA7PnYUJwHVAH5xfHkqyPhll24gVjXRemrkln41MR5BnHVQz8aRSvaCmuFnTLr5dv7dxijPPVGTbeA");'>
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-white font-semibold text-sm drop-shadow-sm">Avocado Toast</h4>
                                        <p class="text-gray-400 text-xs mt-0.5">No chili flakes</p>
                                    </div>
                                    <span class="text-white font-bold text-sm">$8.50</span>
                                </div>
                                <div class="flex items-center gap-3 mt-2">
                                    <button
                                        class="size-7 rounded-full bg-white/5 border border-white/10 text-gray-300 flex items-center justify-center hover:bg-white/10 shadow-[inset_0_0_6px_rgba(255,255,255,0.05)] transition-all">
                                        <span class="material-symbols-outlined text-[16px]">remove</span>
                                    </button>
                                    <span class="text-white text-sm font-bold w-4 text-center">1</span>
                                    <button
                                        class="size-7 rounded-full bg-white/5 border border-white/10 text-gray-300 flex items-center justify-center hover:bg-white/10 shadow-[inset_0_0_6px_rgba(255,255,255,0.05)] transition-all">
                                        <span class="material-symbols-outlined text-[16px]">add</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 bg-black/20 border-t border-white/10 shrink-0 backdrop-blur-md">
                        <div class="flex flex-col gap-2.5 mb-5">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-gray-200 font-medium">$14.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Tax (8%)</span>
                                <span class="text-gray-200 font-medium">$1.12</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Discount</span>
                                <span
                                    class="text-emerald-400 font-medium drop-shadow-[0_0_4px_rgba(52,211,153,0.3)]">-$0.00</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold mt-2 pt-3 border-t border-white/10">
                                <span class="text-white">Total</span>
                                <span class="text-primary drop-shadow-[0_0_8px_rgba(212,115,17,0.5)]">$15.12</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <button
                                class="flex items-center justify-center gap-2 h-12 rounded-xl bg-white/5 border border-white/10 text-gray-200 font-semibold hover:bg-white/10 hover:border-white/20 shadow-[inset_0_0_10px_rgba(255,255,255,0.05)] transition-all">
                                <span class="material-symbols-outlined text-[20px] text-gray-400">payments</span>
                                Cash
                            </button>
                            <button
                                class="flex items-center justify-center gap-2 h-12 rounded-xl bg-white/5 border border-white/10 text-gray-200 font-semibold hover:bg-white/10 hover:border-white/20 shadow-[inset_0_0_10px_rgba(255,255,255,0.05)] transition-all">
                                <span class="material-symbols-outlined text-[20px] text-gray-400">credit_card</span>
                                Card
                            </button>
                        </div>
                        <button
                            class="w-full h-14 rounded-xl bg-primary border border-primary/50 text-white text-lg font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.3),0_0_24px_rgba(212,115,17,0.5)] flex items-center justify-center gap-2 group">
                            Charge $15.12
                            <span
                                class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>
