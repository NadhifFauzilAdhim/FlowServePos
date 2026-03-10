<?php

namespace App\Livewire\Guest;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\Setting;
use App\Services\MenuService;
use App\Services\MidtransService;
use App\Services\OrderService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class GuestOrder extends Component
{
    public Table $table;
    public array $cart = [];
    public ?int $selectedCategory = null;
    public string $searchQuery = '';
    public string $orderNotes = '';
    public bool $showCart = false;
    public bool $showConfirmModal = false;
    public bool $showPaymentMethods = false;
    public bool $orderSuccess = false;
    public ?string $lastOrderNumber = null;
    public ?int $lastOrderId = null;
    public ?string $snapToken = null;
    public bool $showMidtransProcessing = false;
    public ?string $successPaymentMethod = null;

    public function mount(string $token): void
    {
        $this->table = Table::where('qr_token', $token)
            ->where('is_active', true)
            ->firstOrFail();
    }

    #[Computed]
    public function isStoreOpen(): bool
    {
        return (bool) Setting::get('is_store_open', '1');
    }

    public function addToCart(int $menuId): void
    {
        if (! $this->isStoreOpen) return;

        $menu = Menu::find($menuId);
        if (!$menu || !$menu->is_available) return;

        $existingIndex = collect($this->cart)->search(fn($item) => $item['menu_id'] === $menuId);

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

        if (empty($this->cart)) {
            $this->showCart = false;
        }
    }

    public function updateQuantity(int $index, int $delta): void
    {
        if (!isset($this->cart[$index])) return;

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

    public function filterByCategory(?int $id): void
    {
        $this->selectedCategory = $id;
    }

    #[Computed]
    public function subtotal(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function taxAmount(): float
    {
        $taxRate = (float) Setting::get('tax_rate', 8.00);
        return round($this->subtotal * ($taxRate / 100), 2);
    }

    #[Computed]
    public function total(): float
    {
        return $this->subtotal + $this->taxAmount;
    }

    #[Computed]
    public function cartCount(): int
    {
        return collect($this->cart)->sum('quantity');
    }

    public function toggleCart(): void
    {
        $this->showCart = !$this->showCart;
    }

    public function openConfirmModal(): void
    {
        if (! $this->isStoreOpen) return;
        if (empty($this->cart)) return;
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
    }

    /**
     * Submit order — creates order with pending payment status.
     */
    public function submitOrder(): void
    {
        if (! $this->isStoreOpen) return;
        if (empty($this->cart)) return;

        $orderService = app(OrderService::class);
        $order = $orderService->createGuestOrder(
            $this->cart,
            $this->table->number,
            $this->orderNotes ?: null
        );

        $this->lastOrderNumber = $order->order_number;
        $this->lastOrderId = $order->id;
        $this->showConfirmModal = false;
        $this->showCart = false;
        $this->showPaymentMethods = true;
    }

    /**
     * Handle payment method selection.
     */
    public function selectPayment(string $method): void
    {
        $order = Order::find($this->lastOrderId);
        if (!$order) return;

        if ($method === 'cashier') {
            $orderService = app(OrderService::class);
            $orderService->confirmCashierPayment($order);

            $this->showPaymentMethods = false;
            $this->orderSuccess = true;
            $this->successPaymentMethod = 'cashier';
            $this->cart = [];
            $this->orderNotes = '';
        } elseif ($method === 'online') {
            $this->initiateOnlinePayment($order);
        }
    }

    /**
     * Initiate Midtrans online payment.
     */
    private function initiateOnlinePayment(Order $order): void
    {
        try {
            $midtransService = app(MidtransService::class);
            $this->snapToken = $midtransService->createSnapToken($order);

            $this->showPaymentMethods = false;
            $this->showMidtransProcessing = true;

            // Dispatch event to trigger Snap.js popup in browser
            $this->dispatch('open-snap-payment', snapToken: $this->snapToken);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memuat pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Called from JS when Midtrans Snap payment is successful.
     */
    public function midtransPaymentSuccess(): void
    {
        $order = Order::find($this->lastOrderId);
        if (!$order) return;

        // Webhook will handle actual status update, but we update UI state
        $this->showMidtransProcessing = false;
        $this->orderSuccess = true;
        $this->successPaymentMethod = 'online';
        $this->cart = [];
        $this->orderNotes = '';
        $this->snapToken = null;
    }

    /**
     * Called from JS when Midtrans Snap payment is pending.
     */
    public function midtransPaymentPending(): void
    {
        $order = Order::find($this->lastOrderId);
        if (!$order) return;

        $this->showMidtransProcessing = false;
        $this->orderSuccess = true;
        $this->successPaymentMethod = 'online_pending';
        $this->cart = [];
        $this->orderNotes = '';
        $this->snapToken = null;
    }

    /**
     * Called from JS when Midtrans Snap payment fails or is closed.
     */
    public function midtransPaymentFailed(): void
    {
        $this->showMidtransProcessing = false;
        $this->showPaymentMethods = true;
        $this->snapToken = null;
    }

    public function newOrder(): void
    {
        $this->orderSuccess = false;
        $this->showPaymentMethods = false;
        $this->showMidtransProcessing = false;
        $this->lastOrderNumber = null;
        $this->lastOrderId = null;
        $this->snapToken = null;
        $this->successPaymentMethod = null;
    }

    public function render()
    {
        $menuService = app(MenuService::class);

        return view('livewire.guest.guest-order', [
            'menus' => $menuService->getMenusByCategory($this->selectedCategory, $this->searchQuery ?: null),
            'categories' => $menuService->getCategories(),
            'tableNumber' => $this->table->number,
        ])->layoutData(['tableNumber' => $this->table->number]);
    }
}
