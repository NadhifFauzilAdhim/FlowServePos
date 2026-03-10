<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function __construct(
        private readonly MidtransService $midtransService,
    ) {}

    /**
     * Generate a Snap token for the given order.
     */
    public function createSnapToken(Order $order): JsonResponse
    {
        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Order already paid'], 422);
        }

        try {
            $snapToken = $this->midtransService->createSnapToken($order);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error("Midtrans snap token error: {$e->getMessage()}");

            return response()->json(['error' => 'Failed to create payment'], 500);
        }
    }

    /**
     * Handle Midtrans payment notification webhook.
     */
    public function webhook(Request $request): JsonResponse
    {
        try {
            $this->midtransService->handleNotification();

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error("Midtrans webhook error: {$e->getMessage()}");

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}
