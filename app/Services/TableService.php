<?php

namespace App\Services;

use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;

class TableService
{
    public function getAllTables(?string $search = null): Collection
    {
        return Table::query()
            ->when($search, fn($q) => $q->where('number', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%"))
            ->orderBy('number')
            ->get();
    }

    public function createTable(array $data): Table
    {
        $data['qr_token'] = Table::generateQrToken();
        return Table::create($data);
    }

    public function updateTable(Table $table, array $data): Table
    {
        $table->update($data);
        return $table->fresh();
    }

    public function deleteTable(Table $table): void
    {
        $table->delete();
    }

    public function regenerateToken(Table $table): Table
    {
        $table->update(['qr_token' => Table::generateQrToken()]);
        return $table->fresh();
    }
}
