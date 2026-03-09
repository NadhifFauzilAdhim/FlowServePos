<div class="p-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-white text-2xl font-bold drop-shadow-md">Table Management</h1>
            <p class="text-gray-400 text-sm mt-1">Manage restaurant tables and QR codes</p>
        </div>
        <button wire:click="openCreateModal"
            class="px-5 py-2.5 rounded-xl bg-primary border border-primary/50 text-white text-sm font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.3),0_0_24px_rgba(212,115,17,0.5)] flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px]">add</span> Add Table
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <div class="mb-6">
        <div
            class="flex w-full max-w-md items-stretch rounded-lg border border-white/10 bg-black/40 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] overflow-hidden focus-within:border-primary/50 transition-all">
            <div class="text-gray-400 flex items-center justify-center pl-3">
                <span class="material-symbols-outlined text-[18px]">search</span>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="flex w-full min-w-0 flex-1 bg-transparent text-white h-9 px-3 text-sm border-none focus:outline-none focus:ring-0 placeholder:text-gray-500"
                placeholder="Search table number or name..." />
        </div>
    </div>

    {{-- Tables Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse ($tables as $table)
            <div wire:key="table-{{ $table->id }}"
                class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg hover:border-white/20 transition-all group">
                {{-- Table Number --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="size-12 rounded-xl bg-primary/20 border border-primary/30 flex items-center justify-center shadow-[inset_0_0_10px_rgba(212,115,17,0.3)]">
                            <span class="text-primary font-bold text-lg">{{ $table->number }}</span>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base">Meja #{{ $table->number }}</h3>
                            @if ($table->name)
                                <p class="text-gray-400 text-xs">{{ $table->name }}</p>
                            @endif
                        </div>
                    </div>
                    <span
                        class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $table->is_active ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30' }}">
                        {{ $table->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                {{-- QR Token --}}
                <div class="mb-4 px-3 py-2 rounded-lg bg-black/30 border border-white/5">
                    <p class="text-gray-500 text-[10px] uppercase tracking-wider font-semibold mb-0.5">QR Token</p>
                    <p class="text-gray-300 text-xs font-mono">{{ $table->qr_token }}</p>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <button wire:click="showQr({{ $table->id }})"
                        class="flex-1 py-2 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-xs font-medium hover:bg-white/10 transition-all flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">qr_code_2</span> QR Code
                    </button>
                    <button wire:click="openEditModal({{ $table->id }})"
                        class="py-2 px-3 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-xs font-medium hover:bg-white/10 transition-all">
                        <span class="material-symbols-outlined text-[16px]">edit</span>
                    </button>
                    <button wire:click="deleteTable({{ $table->id }})"
                        wire:confirm="Are you sure you want to delete this table?"
                        class="py-2 px-3 rounded-lg bg-white/5 border border-white/10 text-red-400 text-xs font-medium hover:bg-red-500/20 hover:border-red-500/30 transition-all">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <span class="material-symbols-outlined text-5xl text-gray-600 mb-3 block">table_restaurant</span>
                <p class="text-gray-500 text-sm">No tables found</p>
                <p class="text-gray-600 text-xs mt-1">Click "Add Table" to create one</p>
            </div>
        @endforelse
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="closeModal">
            <div class="bg-[#1a1625] border border-white/10 rounded-2xl p-8 w-full max-w-md shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-white text-xl font-bold">
                        {{ $editingId ? 'Edit Table' : 'Add New Table' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="flex flex-col gap-5">
                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-2 block">Table Number *</label>
                        <input wire:model="number" type="number" min="1"
                            class="w-full rounded-xl border border-white/10 bg-black/40 text-white h-11 px-4 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
                        @error('number')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="text-gray-300 text-sm font-medium mb-2 block">Name (optional)</label>
                        <input wire:model="name" type="text" placeholder="e.g. Window Seat, VIP Corner"
                            class="w-full rounded-xl border border-white/10 bg-black/40 text-white h-11 px-4 text-sm focus:border-primary/50 focus:outline-none focus:ring-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]" />
                        @error('name')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input wire:model="is_active" type="checkbox" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-600 rounded-full peer peer-checked:bg-emerald-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                            </div>
                        </label>
                        <span class="text-gray-300 text-sm">Active</span>
                    </div>
                </div>

                <button wire:click="save"
                    class="w-full mt-6 h-12 rounded-xl bg-primary border border-primary/50 text-white text-sm font-bold hover:bg-primary/90 transition-all shadow-[inset_0_0_16px_rgba(255,255,255,0.3),0_0_24px_rgba(212,115,17,0.5)] flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    {{ $editingId ? 'Update Table' : 'Create Table' }}
                </button>
            </div>
        </div>
    @endif

    {{-- QR Code Modal --}}
    @if ($showQrModal && $viewingTable)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="closeQrModal" x-data="{
                generateQR() {
                        const container = this.$refs.qrContainer;
                        if (!container) return;
                        container.innerHTML = '';
                        new QRCode(container, {
                            text: '{{ $viewingTable->qr_url }}',
                            width: 200,
                            height: 200,
                            colorDark: '#000000',
                            colorLight: '#ffffff',
                            correctLevel: QRCode.CorrectLevel.H
                        });
                    },
                    downloadQR() {
                        const canvas = this.$refs.qrContainer.querySelector('canvas');
                        if (!canvas) return;
            
                        const printCanvas = document.createElement('canvas');
                        const ctx = printCanvas.getContext('2d');
                        printCanvas.width = 400;
                        printCanvas.height = 500;
            
                        ctx.fillStyle = '#ffffff';
                        ctx.fillRect(0, 0, 400, 500);
            
                        ctx.drawImage(canvas, 100, 40, 200, 200);
            
                        ctx.fillStyle = '#000000';
                        ctx.font = 'bold 36px Arial';
                        ctx.textAlign = 'center';
                        ctx.fillText('Meja #{{ $viewingTable->number }}', 200, 300);
            
                        ctx.font = '16px Arial';
                        ctx.fillStyle = '#666666';
                        ctx.fillText('Scan untuk memesan', 200, 340);
            
                        ctx.font = 'bold 20px Arial';
                        ctx.fillStyle = '#333333';
                        ctx.fillText('☕ FlowServe', 200, 400);
            
                        const link = document.createElement('a');
                        link.download = 'qr-meja-{{ $viewingTable->number }}.png';
                        link.href = printCanvas.toDataURL('image/png');
                        link.click();
                    },
                    printQR() {
                        const canvas = this.$refs.qrContainer.querySelector('canvas');
                        if (!canvas) return;
            
                        const printWin = window.open('', '_blank', 'width=450,height=600');
                        printWin.document.write(`<!DOCTYPE html><html><head><title>QR Meja {{ $viewingTable->number }}</title>
                                                <style>
                                                    body { margin:0; display:flex; justify-content:center; align-items:center; min-height:100vh; font-family:Arial,sans-serif; }
                                                    .card { text-align:center; padding:40px; border:3px dashed #ccc; border-radius:16px; }
                                                    .card img { margin:0 auto 20px; }
                                                    .table-num { font-size:36px; font-weight:bold; margin-bottom:8px; }
                                                    .subtitle { color:#666; font-size:14px; margin-bottom:24px; }
                                                    .brand { font-size:18px; font-weight:bold; color:#333; }
                                                </style>
                                            </head><body><div class='card'>
                                                <img src='${canvas.toDataURL('image/png')}' width='200' height='200' />
                                                <div class='table-num'>Meja #{{ $viewingTable->number }}</div>
                                                <div class='subtitle'>Scan untuk memesan</div>
                                                <div class='brand'>☕ FlowServe</div>
                                            </div></body></html>`);
                        printWin.document.close();
                        printWin.focus();
                        setTimeout(() => {
                            printWin.print();
                            printWin.close();
                        }, 300);
                    }
            }" x-init="$nextTick(() => generateQR())">
            <div class="bg-[#1a1625] border border-white/10 rounded-2xl p-8 w-full max-w-sm shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-white text-xl font-bold">QR Code — Meja #{{ $viewingTable->number }}</h3>
                    <button wire:click="closeQrModal" class="text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- QR Display --}}
                <div class="flex flex-col items-center">
                    <div class="bg-white rounded-2xl p-6 mb-4 shadow-lg">
                        <div x-ref="qrContainer" class="flex items-center justify-center"
                            style="min-width:200px;min-height:200px;"></div>
                    </div>
                    <p class="text-white text-lg font-bold mb-1">Meja #{{ $viewingTable->number }}</p>
                    @if ($viewingTable->name)
                        <p class="text-gray-400 text-sm mb-1">{{ $viewingTable->name }}</p>
                    @endif
                    <p class="text-gray-500 text-xs font-mono break-all text-center mb-6">{{ $viewingTable->qr_url }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-2.5">
                    <button @click="downloadQR()"
                        class="w-full h-11 rounded-xl bg-white/5 border border-white/10 text-gray-200 text-sm font-bold hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">download</span> Download PNG
                    </button>
                    <button @click="printQR()"
                        class="w-full h-11 rounded-xl bg-white/5 border border-white/10 text-gray-200 text-sm font-bold hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">print</span> Print QR Card
                    </button>
                    <button wire:click="regenerateQr({{ $viewingTable->id }})"
                        wire:confirm="This will invalidate the current QR code. Continue?"
                        class="w-full h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 text-sm font-bold hover:bg-amber-500/20 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">refresh</span> Regenerate QR
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
@endpush
