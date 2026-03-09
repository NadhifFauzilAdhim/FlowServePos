<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function calculateTotals(array $cartItems, float $discount = 0): array
    {
        $taxRate = (float) Setting::get('tax_rate', 8.00);
        $subtotal = collect($cartItems)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $total = $subtotal + $taxAmount - $discount;

        return [
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discount,
            'total' => max(0, $total),
        ];
    }

    public function createOrder(
        array $cartItems,
        string $orderType,
        float $discount,
        float $amountReceived,
        int $userId,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($cartItems, $orderType, $discount, $amountReceived, $userId, $notes) {
            $totals = $this->calculateTotals($cartItems, $discount);
            $changeAmount = $amountReceived - $totals['total'];

            $order = Order::create([
                'user_id' => $userId,
                'order_number' => Order::generateOrderNumber(),
                'order_type' => $orderType,
                'subtotal' => $totals['subtotal'],
                'tax_rate' => $totals['tax_rate'],
                'tax_amount' => $totals['tax_amount'],
                'discount_amount' => $totals['discount_amount'],
                'total' => $totals['total'],
                'amount_received' => $amountReceived,
                'change_amount' => max(0, $changeAmount),
                'status' => 'pending',
                'notes' => $notes,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            return $order->load('items.menu');
        });
    }

    public function createGuestOrder(
        array $cartItems,
        int $tableNumber,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($cartItems, $tableNumber, $notes) {
            $totals = $this->calculateTotals($cartItems);

            $order = Order::create([
                'user_id' => null,
                'order_number' => Order::generateOrderNumber(),
                'order_type' => 'dine_in',
                'table_number' => $tableNumber,
                'subtotal' => $totals['subtotal'],
                'tax_rate' => $totals['tax_rate'],
                'tax_amount' => $totals['tax_amount'],
                'discount_amount' => 0,
                'total' => $totals['total'],
                'amount_received' => $totals['total'],
                'change_amount' => 0,
                'status' => 'waiting_confirmation',
                'notes' => $notes,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            Cache::forget('pos_waiting_orders');

            return $order->load('items.menu');
        });
    }

    public function cancelOrder(Order $order): void
    {
        $order->update(['status' => 'cancelled']);
    }

    public function updateStatus(Order $order, string $status): void
    {
        $allowed = ['pending', 'preparing', 'ready', 'completed', 'cancelled'];
        if (in_array($status, $allowed)) {
            $order->update(['status' => $status]);
        }
    }

    public function getKitchenOrders()
    {
        return Order::today()
            ->active()
            ->with('items.menu', 'user')
            ->oldest()
            ->get();
    }

    public function getTodayOrders()
    {
        return Order::today()
            ->with('items.menu', 'user')
            ->latest()
            ->get();
    }
}
