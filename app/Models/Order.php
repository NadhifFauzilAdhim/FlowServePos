<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'order_type',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total',
        'amount_received',
        'change_amount',
        'table_number',
        'status',
        'payment_status',
        'payment_method',
        'midtrans_snap_token',
        'midtrans_transaction_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'amount_received' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopePreparing(Builder $query): Builder
    {
        return $query->where('status', 'preparing');
    }

    public function scopeReady(Builder $query): Builder
    {
        return $query->where('status', 'ready');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'preparing', 'ready'])
            ->where(function (Builder $q) {
                $q->where('payment_status', 'paid')
                    ->orWhere('payment_method', 'cashier');
            });
    }

    public function scopeWaitingConfirmation(Builder $query): Builder
    {
        return $query->where('status', 'waiting_confirmation');
    }

    public function scopePaymentPending(Builder $query): Builder
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePaymentPaid(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePaidOnline(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid')
            ->where('payment_method', 'online_payment');
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isOnlinePayment(): bool
    {
        return $this->payment_method === 'online_payment';
    }

    public static function generateOrderNumber(): string
    {
        $today = now()->format('Ymd');
        $lastOrder = static::where('order_number', 'like', "ORD-{$today}-%")
            ->orderByDesc('order_number')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return "ORD-{$today}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
