<?php

namespace App\Livewire\Guest;

use App\Models\Menu;
use App\Models\Table;
use App\Services\MenuService;
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

    public function mount(string $token): void
    {
        $this->table = Table::where('qr_token', $token)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function addToCart(int $menuId): void
    {
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
        return round($this->subtotal * (OrderService::TAX_RATE / 100), 2);
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
        if (empty($this->cart)) return;
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
    }

    public function submitOrder(): void
    {
        if (empty($this->cart)) return;

        $orderService = app(OrderService::class);
        $order = $orderService->createGuestOrder(
            $this->cart,
            $this->table->number,
            $this->orderNotes ?: null
        );

        $this->lastOrderNumber = $order->order_number;
        $this->showConfirmModal = false;
        $this->showCart = false;
        $this->showPaymentMethods = true;
    }

    public function selectPayment(string $method): void
    {
        if ($method === 'cashier') {
            $this->showPaymentMethods = false;
            $this->orderSuccess = true;
            $this->cart = [];
            $this->orderNotes = '';
        }
        // QRIS: coming soon
    }

    public function newOrder(): void
    {
        $this->orderSuccess = false;
        $this->showPaymentMethods = false;
        $this->lastOrderNumber = null;
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
