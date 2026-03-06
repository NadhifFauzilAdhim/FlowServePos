<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'qr_token',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'table_number', 'number');
    }

    public static function generateQrToken(): string
    {
        do {
            $token = Str::random(12);
        } while (static::where('qr_token', $token)->exists());

        return $token;
    }

    public function getQrUrlAttribute(): string
    {
        return url('/order/' . $this->qr_token);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ? "Meja #{$this->number} ({$this->name})" : "Meja #{$this->number}";
    }
}
