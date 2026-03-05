<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\InventoryLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function getAllItems(?string $search = null): Collection
    {
        return InventoryItem::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->get();
    }

    public function getLowStockItems(): Collection
    {
        return InventoryItem::lowStock()->orderBy('name')->get();
    }

    public function createItem(array $data): InventoryItem
    {
        return InventoryItem::create($data);
    }

    public function updateItem(InventoryItem $item, array $data): InventoryItem
    {
        $item->update($data);
        return $item->fresh();
    }

    public function deleteItem(InventoryItem $item): void
    {
        $item->delete();
    }

    public function adjustStock(
        InventoryItem $item,
        float $quantity,
        string $type,
        string $notes,
        int $userId
    ): void {
        DB::transaction(function () use ($item, $quantity, $type, $notes, $userId) {
            $previousStock = $item->current_stock;

            $newStock = match ($type) {
                'in' => $previousStock + $quantity,
                'out' => max(0, $previousStock - $quantity),
                'adjustment' => $quantity,
            };

            $item->update(['current_stock' => $newStock]);

            InventoryLog::create([
                'inventory_item_id' => $item->id,
                'user_id' => $userId,
                'type' => $type,
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'notes' => $notes,
            ]);
        });
    }

    public function getItemHistory(InventoryItem $item, int $limit = 20): Collection
    {
        return $item->logs()
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
