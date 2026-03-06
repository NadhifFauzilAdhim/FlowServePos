<?php

namespace App\Livewire\Kitchen;

use App\Models\Order;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.kitchen')]
class KitchenDisplay extends Component
{
    public int $previousCount = 0;
    public bool $hasNewOrder = false;

    public function markPreparing(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        app(OrderService::class)->updateStatus($order, 'preparing');
    }

    public function markReady(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        app(OrderService::class)->updateStatus($order, 'ready');
    }

    public function markCompleted(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        app(OrderService::class)->updateStatus($order, 'completed');
    }

    public function render()
    {
        $orders = app(OrderService::class)->getKitchenOrders();

        $pendingOrders = $orders->where('status', 'pending');
        $preparingOrders = $orders->where('status', 'preparing');
        $readyOrders = $orders->where('status', 'ready');

        // Detect new orders for audio notification
        $currentCount = $pendingOrders->count();
        if ($currentCount > $this->previousCount && $this->previousCount > 0) {
            $this->hasNewOrder = true;
            $this->dispatch('new-kitchen-order');
        } else {
            $this->hasNewOrder = false;
        }
        $this->previousCount = $currentCount;

        return view('livewire.kitchen.kitchen-display', [
            'pendingOrders' => $pendingOrders,
            'preparingOrders' => $preparingOrders,
            'readyOrders' => $readyOrders,
            'totalActive' => $orders->count(),
        ]);
    }
}
