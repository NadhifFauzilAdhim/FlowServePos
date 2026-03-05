<?php

namespace App\Livewire\Admin;

use App\Models\InventoryItem;
use App\Services\InventoryService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class InventoryManagement extends Component
{
    public string $search = '';
    public bool $showModal = false;
    public bool $showAdjustModal = false;
    public ?int $editingId = null;
    public ?int $adjustingId = null;

    // Item form
    public string $name = '';
    public string $sku = '';
    public string $unit = 'pcs';
    public float $current_stock = 0;
    public float $min_stock = 0;
    public float $cost_per_unit = 0;

    // Adjust form
    public float $adjustQuantity = 0;
    public string $adjustType = 'in';
    public string $adjustNotes = '';

    protected function rules(): array
    {
        $skuRule = $this->editingId
            ? "required|unique:inventory_items,sku,{$this->editingId}"
            : 'required|unique:inventory_items,sku';

        return [
            'name' => 'required|min:2|max:255',
            'sku' => $skuRule,
            'unit' => 'required|max:20',
            'current_stock' => 'numeric|min:0',
            'min_stock' => 'numeric|min:0',
            'cost_per_unit' => 'numeric|min:0',
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $item = InventoryItem::findOrFail($id);
        $this->editingId = $item->id;
        $this->name = $item->name;
        $this->sku = $item->sku;
        $this->unit = $item->unit;
        $this->current_stock = (float) $item->current_stock;
        $this->min_stock = (float) $item->min_stock;
        $this->cost_per_unit = (float) $item->cost_per_unit;
        $this->showModal = true;
    }

    public function openAdjustModal(int $id): void
    {
        $this->adjustingId = $id;
        $this->adjustQuantity = 0;
        $this->adjustType = 'in';
        $this->adjustNotes = '';
        $this->showAdjustModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $service = app(InventoryService::class);
        $data = [
            'name' => $this->name,
            'sku' => $this->sku,
            'unit' => $this->unit,
            'current_stock' => $this->current_stock,
            'min_stock' => $this->min_stock,
            'cost_per_unit' => $this->cost_per_unit,
        ];

        if ($this->editingId) {
            $item = InventoryItem::findOrFail($this->editingId);
            $service->updateItem($item, $data);
            session()->flash('success', 'Item updated successfully.');
        } else {
            $service->createItem($data);
            session()->flash('success', 'Item created successfully.');
        }

        $this->closeModal();
    }

    public function adjustStock(): void
    {
        $this->validate([
            'adjustQuantity' => 'required|numeric|min:0.01',
            'adjustType' => 'required|in:in,out,adjustment',
            'adjustNotes' => 'nullable|max:500',
        ]);

        $item = InventoryItem::findOrFail($this->adjustingId);
        app(InventoryService::class)->adjustStock(
            $item,
            $this->adjustQuantity,
            $this->adjustType,
            $this->adjustNotes ?: 'Manual adjustment',
            auth()->id()
        );

        $this->showAdjustModal = false;
        session()->flash('success', 'Stock adjusted successfully.');
    }

    public function deleteItem(int $id): void
    {
        $item = InventoryItem::findOrFail($id);
        app(InventoryService::class)->deleteItem($item);
        session()->flash('success', 'Item deleted successfully.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->sku = '';
        $this->unit = 'pcs';
        $this->current_stock = 0;
        $this->min_stock = 0;
        $this->cost_per_unit = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $service = app(InventoryService::class);

        return view('livewire.admin.inventory-management', [
            'items' => $service->getAllItems($this->search ?: null),
            'lowStockCount' => InventoryItem::lowStock()->count(),
        ]);
    }
}
