<?php

namespace App\Livewire\Admin;

use App\Models\Table;
use App\Services\TableService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TableManagement extends Component
{
    public string $search = '';

    public bool $showModal = false;

    public bool $showQrModal = false;

    public ?int $editingId = null;

    public ?Table $viewingTable = null;

    // Form fields
    public int $number = 1;

    public string $name = '';

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'number' => 'required|integer|min:1|unique:tables,number,'.$this->editingId,
            'name' => 'nullable|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $nextNumber = Table::max('number') + 1;
        $this->number = $nextNumber ?: 1;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $table = Table::findOrFail($id);
        $this->editingId = $table->id;
        $this->number = $table->number;
        $this->name = $table->name ?? '';
        $this->is_active = $table->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $tableService = app(TableService::class);
        $data = [
            'number' => $this->number,
            'name' => $this->name ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            $table = Table::findOrFail($this->editingId);
            $tableService->updateTable($table, $data);
            session()->flash('success', 'Table updated successfully.');
        } else {
            $tableService->createTable($data);
            session()->flash('success', 'Table created successfully.');
        }

        $this->closeModal();
    }

    public function deleteTable(int $id): void
    {
        $table = Table::findOrFail($id);
        app(TableService::class)->deleteTable($table);
        session()->flash('success', 'Table deleted successfully.');
    }

    public function showQr(int $id): void
    {
        $this->viewingTable = Table::findOrFail($id);
        $this->showQrModal = true;
    }

    public function closeQrModal(): void
    {
        $this->showQrModal = false;
        $this->viewingTable = null;
    }

    public function regenerateQr(int $id): void
    {
        $table = Table::findOrFail($id);
        app(TableService::class)->regenerateToken($table);
        $this->viewingTable = $table->fresh();
        session()->flash('success', 'QR code regenerated.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->number = 1;
        $this->name = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.table-management', [
            'tables' => app(TableService::class)->getAllTables($this->search ?: null),
        ]);
    }
}
