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

    /**
     * Create a POS order (cashier-initiated, already paid).
     */
    public function createOrder(
        array $cartItems,
        string $orderType,
        float $discount,
        float $amountReceived,
        int $userId,
        ?string $notes = null,
        string $paymentMethod = 'cashier'
    ): Order {
        return DB::transaction(function () use ($cartItems, $orderType, $discount, $amountReceived, $userId, $notes, $paymentMethod) {
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
                'payment_status' => 'paid',
                'payment_method' => $paymentMethod,
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

    /**
     * Create a guest order (QR order, payment pending).
     */
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
                'amount_received' => 0,
                'change_amount' => 0,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => null,
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

    /**
     * Confirm that a guest order will be paid at the cashier.
     */
    public function confirmCashierPayment(Order $order): void
    {
        $order->update([
            'payment_method' => 'cashier',
            'status' => 'waiting_confirmation',
        ]);

        Cache::forget('pos_waiting_orders');
    }

    public function cancelOrder(Order $order): void
    {
        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);
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

    /**
     * Get orders waiting for cashier confirmation OR paid online and pending processing.
     */
    public function getWaitingOrders()
    {
        return Order::where(function ($q) {
            // Traditional: guest orders waiting for cashier confirmation
            $q->where('status', 'waiting_confirmation')
                ->where('payment_method', 'cashier')
                ->whereNull('user_id');
        })->orWhere(function ($q) {
            // Online paid guest orders: ready to be processed
            $q->where('payment_status', 'paid')
                ->where('payment_method', 'online_payment')
                ->where('status', 'pending')
                ->whereNull('user_id');
        })
            ->with('items.menu')
            ->latest()
            ->get();
    }
}
