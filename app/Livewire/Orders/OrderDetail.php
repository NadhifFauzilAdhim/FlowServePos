<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class OrderDetail extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order->load('items.menu', 'user');
    }

    public function cancelOrder(): void
    {
        app(OrderService::class)->cancelOrder($this->order);
        $this->order->refresh();
        session()->flash('success', 'Order cancelled successfully.');
    }

    public function render()
    {
        return view('livewire.orders.order-detail');
    }
}
