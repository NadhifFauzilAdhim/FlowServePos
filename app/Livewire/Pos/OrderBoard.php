<?php

namespace App\Livewire\Pos;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Setting;
use App\Services\MenuService;
use App\Services\MidtransService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class OrderBoard extends Component
{
    public array $cart = [];

    public ?int $selectedCategory = null;

    public string $searchQuery = '';

    public string $orderType = 'dine_in';

    public float $discount = 0;

    public float $amountReceived = 0;

    public bool $showPaymentModal = false;

    public bool $showReceiptModal = false;

    public bool $showRejectModal = false;

    public ?int $rejectingQrOrderId = null;

    public ?string $rejectingQrOrderNumber = null;

    public ?string $rejectingQrOrderTable = null;

    public ?int $payingQrOrderId = null;

    public ?array $payingQrOrderData = null;

    public ?array $lastOrder = null;

    public ?int $previousWaitingCount = null;

    public string $posPaymentMethod = 'cash';

    #[Computed]
    public function isStoreOpen(): bool
    {
        return (bool) Setting::get('is_store_open', '1');
    }

    public function addToCart(int $menuId): void
    {
        if (! $this->isStoreOpen) {
            session()->flash('error', 'Toko Sedang Tutup.');
            return;
        }

        $menu = Menu::find($menuId);
        if (! $menu || ! $menu->is_available) {
            return;
        }

        $existingIndex = collect($this->cart)->search(fn ($item) => $item['menu_id'] === $menuId);

        if ($existingIndex !== false) {
            $this->cart[$existingIndex]['quantity']++;
        } else {
            $this->cart[] = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => (float) $menu->price,
                'quantity' => 1,
                'image' => $menu->image,
                'notes' => '',
            ];
        }
    }

    public function removeFromCart(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function updateQuantity(int $index, int $delta): void
    {
        if (! isset($this->cart[$index])) {
            return;
        }

        $this->cart[$index]['quantity'] += $delta;

        if ($this->cart[$index]['quantity'] <= 0) {
            $this->removeFromCart($index);
        }
    }

    public function updateNote(int $index, string $note): void
    {
        if (isset($this->cart[$index])) {
            $this->cart[$index]['notes'] = $note;
        }
    }

    public function setOrderType(string $type): void
    {
        $this->orderType = $type;
    }

    public function filterByCategory(?int $id): void
    {
        $this->selectedCategory = $id;
    }

    #[Computed]
    public function subtotal(): float
    {
        return collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function taxAmount(): float
    {
        $taxRate = (float) \App\Models\Setting::get('tax_rate', 8.00);
        return round($this->subtotal * ($taxRate / 100), 2);
    }

    #[Computed]
    public function total(): float
    {
        return max(0, $this->subtotal + $this->taxAmount - $this->discount);
    }

    #[Computed]
    public function change(): float
    {
        if ($this->payingQrOrderId && $this->payingQrOrderData) {
            return max(0, $this->amountReceived - $this->payingQrOrderData['total']);
        }

        return max(0, $this->amountReceived - $this->total);
    }

    public function openPaymentModal(): void
    {
        if (! $this->isStoreOpen) {
            session()->flash('error', 'Toko Sedang Tutup.');
            return;
        }

        if (empty($this->cart)) {
            return;
        }
        $this->amountReceived = $this->total;
        $this->posPaymentMethod = 'cash';
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->payingQrOrderId = null;
        $this->payingQrOrderData = null;
        $this->posPaymentMethod = 'cash';
    }

    public function setPosPaymentMethod(string $method): void
    {
        $this->posPaymentMethod = $method;
        if ($method === 'qris') {
            $this->amountReceived = $this->payingQrOrderId
                ? $this->payingQrOrderData['total']
                : $this->total;
        }
    }

    public function processPayment(): void
    {
        if ($this->payingQrOrderId) {
            $this->processQrOrderPayment();
            return;
        }

        if (empty($this->cart)) {
            return;
        }

        if ($this->posPaymentMethod === 'qris') {
            $this->processPosQrisPayment();
            return;
        }

        if ($this->amountReceived < $this->total) {
            $this->addError('amountReceived', 'Amount received is less than total.');
            return;
        }

        $orderService = app(OrderService::class);
        $order = $orderService->createOrder(
            $this->cart,
            $this->orderType,
            $this->discount,
            $this->amountReceived,
            auth()->id()
        );

        $this->setLastOrderData($order);
        $this->resetCartState();
        $this->showReceiptModal = true;
        $this->dispatch('order-completed');
    }

    /**
     * Process a POS order with QRIS payment via Midtrans.
     */
    private function processPosQrisPayment(): void
    {
        $orderService = app(OrderService::class);
        $order = $orderService->createOrder(
            $this->cart,
            $this->orderType,
            $this->discount,
            $this->total,
            auth()->id(),
            null,
            'online_payment'
        );

        try {
            $midtransService = app(MidtransService::class);
            $snapToken = $midtransService->createSnapToken($order);

            $this->dispatch('open-pos-snap-payment', snapToken: $snapToken, orderId: $order->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create QRIS payment. Try again.');
        }
    }

    /**
     * Called from JS when POS Snap payment succeeds.
     */
    public function posQrisPaymentSuccess(int $orderId): void
    {
        $order = Order::with('items.menu')->findOrFail($orderId);

        $this->setLastOrderData($order);
        $this->resetCartState();
        $this->showReceiptModal = true;
        $this->dispatch('order-completed');
    }

    /**
     * Called from JS when POS Snap payment fails.
     */
    public function posQrisPaymentFailed(int $orderId): void
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);
        }
        session()->flash('error', 'QRIS payment cancelled or failed.');
        $this->closePaymentModal();
    }

    public function processQrOrderPayment(): void
    {
        $order = Order::waitingConfirmation()->with('items.menu')->findOrFail($this->payingQrOrderId);
        $total = $order->total;

        if ($this->amountReceived < $total) {
            $this->addError('amountReceived', 'Amount received is less than total.');
            return;
        }

        $changeAmount = $this->amountReceived - $total;

        $order->update([
            'status' => 'pending',
            'payment_status' => 'paid',
            'payment_method' => 'cashier',
            'amount_received' => $this->amountReceived,
            'change_amount' => max(0, $changeAmount),
            'user_id' => auth()->id(),
        ]);

        $this->setLastOrderData($order);
        $this->payingQrOrderId = null;
        $this->payingQrOrderData = null;
        $this->amountReceived = 0;
        $this->showPaymentModal = false;
        $this->showReceiptModal = true;

        session()->flash('success', "Pesanan {$order->order_number} " . ($order->table_number ? "(Meja #{$order->table_number}) " : '') . 'dikonfirmasi.');
        Cache::forget('pos_waiting_orders');
        $this->dispatch('order-completed');
    }

    /**
     * Accept an online-paid order (no payment needed, already paid via Midtrans).
     */
    public function acceptOnlinePaidOrder(int $orderId): void
    {
        $order = Order::where('payment_status', 'paid')
            ->where('payment_method', 'online_payment')
            ->where('status', 'pending')
            ->with('items.menu')
            ->findOrFail($orderId);

        $order->update([
            'user_id' => auth()->id(),
        ]);

        session()->flash('success', "Pesanan online {$order->order_number} " . ($order->table_number ? "(Meja #{$order->table_number}) " : '') . 'diterima. Pembayaran sudah terverifikasi.');
        Cache::forget('pos_waiting_orders');
        $this->dispatch('order-completed');
    }

    public function confirmQrOrder(int $orderId): void
    {
        $order = Order::waitingConfirmation()->with('items')->findOrFail($orderId);
        $this->payingQrOrderId = $order->id;
        $this->payingQrOrderData = [
            'total' => $order->total,
            'item_count' => $order->items->sum('quantity'),
            'order_type' => $order->order_type,
            'table_number' => $order->table_number,
        ];
        $this->amountReceived = $order->total;
        $this->posPaymentMethod = 'cash';
        $this->showPaymentModal = true;
    }

    public function openRejectModal(int $orderId): void
    {
        $order = Order::waitingConfirmation()->findOrFail($orderId);
        $this->rejectingQrOrderId = $order->id;
        $this->rejectingQrOrderNumber = $order->order_number;
        $this->rejectingQrOrderTable = $order->table_number;
        $this->showRejectModal = true;
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->rejectingQrOrderId = null;
        $this->rejectingQrOrderNumber = null;
        $this->rejectingQrOrderTable = null;
    }

    public function rejectQrOrder(): void
    {
        if (! $this->rejectingQrOrderId) {
            return;
        }

        $order = Order::waitingConfirmation()->findOrFail($this->rejectingQrOrderId);
        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        session()->flash('success', "Pesanan {$this->rejectingQrOrderNumber} ditolak.");
        Cache::forget('pos_waiting_orders');
        $this->closeRejectModal();
    }

    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->lastOrder = null;
    }

    public function printReceipt(): void
    {
        $this->dispatch('print-receipt');
    }

    public function resetCart(): void
    {
        $this->cart = [];
        $this->discount = 0;
        $this->amountReceived = 0;
        $this->orderType = 'dine_in';
        $this->lastOrder = null;
        $this->showReceiptModal = false;
        $this->posPaymentMethod = 'cash';
    }

    private function setLastOrderData(Order $order): void
    {
        $this->lastOrder = [
            'order_number' => $order->order_number,
            'order_type' => ucfirst(str_replace('_', ' ', $order->order_type)) . ($order->table_number ? " (Meja #{$order->table_number})" : ''),
            'cashier' => auth()->user()->name,
            'date' => $order->created_at->format('d/m/Y H:i'),
            'payment_method' => $order->payment_method === 'online_payment' ? 'QRIS' : 'Tunai',
            'items' => $order->items->map(fn ($item) => [
                'name' => $item->menu->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
                'notes' => $item->notes,
            ])->toArray(),
            'subtotal' => $order->subtotal,
            'tax_rate' => $order->tax_rate,
            'tax_amount' => $order->tax_amount,
            'discount_amount' => $order->discount_amount,
            'total' => $order->total,
            'amount_received' => $order->amount_received,
            'change_amount' => $order->change_amount,
        ];
    }

    private function resetCartState(): void
    {
        $this->cart = [];
        $this->discount = 0;
        $this->amountReceived = 0;
        $this->orderType = 'dine_in';
        $this->showPaymentModal = false;
        $this->posPaymentMethod = 'cash';
    }

    public function render()
    {
        $menuService = app(MenuService::class);

        return view('livewire.pos.order-board', [
            'menus' => $menuService->getMenusByCategory($this->selectedCategory, $this->searchQuery ?: null),
            'categories' => $menuService->getCategories(),
        ]);
    }
}
