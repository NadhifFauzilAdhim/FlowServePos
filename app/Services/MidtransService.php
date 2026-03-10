<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create a Snap token for the given order.
     */
    public function createSnapToken(Order $order): string
    {
        // Build finish redirect URL
        $finishUrl = url('/');
        if ($order->table_number) {
            $table = \App\Models\Table::where('number', $order->table_number)->first();
            if ($table) {
                $finishUrl = route('guest.order', $table->qr_token);
            }
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number.'-'.time(),
                'gross_amount' => (int) $order->total,
            ],
            'item_details' => $order->items->map(fn ($item) => [
                'id' => $item->menu_id,
                'name' => $item->menu->name,
                'price' => (int) $item->unit_price,
                'quantity' => $item->quantity,
            ])->toArray(),
            'callbacks' => [
                'finish' => $finishUrl,
            ],
        ];

        // Add tax as item detail if present
        if ($order->tax_amount > 0) {
            $params['item_details'][] = [
                'id' => 'TAX',
                'name' => "Pajak ({$order->tax_rate}%)",
                'price' => (int) $order->tax_amount,
                'quantity' => 1,
            ];
        }

        $snapToken = Snap::getSnapToken($params);

        $order->update([
            'midtrans_snap_token' => $snapToken,
            'payment_method' => 'online_payment',
        ]);

        return $snapToken;
    }

    /**
     * Handle incoming Midtrans payment notification (webhook).
     */
    public function handleNotification(): void
    {
        $notification = new Notification;

        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;
        $orderId = $notification->order_id;

        // Extract original order number (remove timestamp suffix)
        $orderNumber = preg_replace('/-\d+$/', '', $orderId);

        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            Log::warning("Midtrans webhook: Order not found for {$orderId}");

            return;
        }

        Log::info("Midtrans webhook: Order {$orderNumber}, status={$transactionStatus}, fraud={$fraudStatus}");

        match ($transactionStatus) {
            'capture' => $this->handleCapture($order, $fraudStatus),
            'settlement' => $this->handleSettlement($order),
            'pending' => $this->handlePending($order),
            'deny', 'cancel', 'expire' => $this->handleFailure($order, $transactionStatus),
            default => Log::info("Midtrans webhook: Unhandled status {$transactionStatus} for {$orderNumber}"),
        };

        $order->update(['midtrans_transaction_id' => $notification->transaction_id ?? null]);
    }

    private function handleCapture(Order $order, ?string $fraudStatus): void
    {
        if ($fraudStatus === 'accept') {
            $this->markAsPaid($order);
        } else {
            Log::warning("Midtrans: Order {$order->order_number} captured with fraud status: {$fraudStatus}");
        }
    }

    private function handleSettlement(Order $order): void
    {
        $this->markAsPaid($order);
    }

    private function handlePending(Order $order): void
    {
        $order->update(['payment_status' => 'pending']);
    }

    private function handleFailure(Order $order, string $status): void
    {
        $order->update([
            'payment_status' => 'failed',
        ]);

        Log::info("Midtrans: Order {$order->order_number} payment {$status}");
    }

    private function markAsPaid(Order $order): void
    {
        $order->update([
            'payment_status' => 'paid',
            'payment_method' => 'online_payment',
            'status' => 'pending',
            'amount_received' => $order->total,
            'change_amount' => 0,
        ]);

        // Clear cache so POS picks up new orders
        Cache::forget('pos_waiting_orders');

        Log::info("Midtrans: Order {$order->order_number} marked as paid");
    }
}
