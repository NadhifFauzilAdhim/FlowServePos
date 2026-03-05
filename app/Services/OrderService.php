<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class OrderService
{
    const TAX_RATE = 8.00;

    public function calculateTotals(array $cartItems, float $discount = 0): array
    {
        $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        $taxAmount = round($subtotal * (self::TAX_RATE / 100), 2);
        $total = $subtotal + $taxAmount - $discount;

        return [
            'subtotal' => $subtotal,
            'tax_rate' => self::TAX_RATE,
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
                'status' => 'completed',
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

    public function cancelOrder(Order $order): void
    {
        $order->update(['status' => 'cancelled']);
    }

    public function getTodayOrders()
    {
        return Order::today()
            ->with('items.menu', 'user')
            ->latest()
            ->get();
    }
}
