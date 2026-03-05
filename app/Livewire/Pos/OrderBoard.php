<?php

namespace App\Livewire\Pos;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Services\MenuService;
use App\Services\OrderService;
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
    public ?array $lastOrder = null;

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
        return max(0, $this->subtotal + $this->taxAmount - $this->discount);
    }

    #[Computed]
    public function change(): float
    {
        return max(0, $this->amountReceived - $this->total);
    }

    public function openPaymentModal(): void
    {
        if (empty($this->cart)) return;
        $this->amountReceived = $this->total;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
    }

    public function processPayment(): void
    {
        if (empty($this->cart)) return;
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

        $this->lastOrder = [
            'order_number' => $order->order_number,
            'total' => $order->total,
            'amount_received' => $order->amount_received,
            'change_amount' => $order->change_amount,
        ];

        $this->resetCart();
        $this->showPaymentModal = false;

        $this->dispatch('order-completed');
    }

    public function resetCart(): void
    {
        $this->cart = [];
        $this->discount = 0;
        $this->amountReceived = 0;
        $this->orderType = 'dine_in';
        $this->lastOrder = null;
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
