<?php

namespace App\Livewire\Admin;

use App\Models\MenuCategory;
use App\Services\MenuService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CategoryManagement extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $icon = '';
    public int $sort_order = 0;

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'icon' => 'nullable|max:50',
        'sort_order' => 'integer|min:0',
    ];

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $category = MenuCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->icon = $category->icon ?? '';
        $this->sort_order = $category->sort_order;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $menuService = app(MenuService::class);
        $data = [
            'name' => $this->name,
            'icon' => $this->icon ?: null,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            $category = MenuCategory::findOrFail($this->editingId);
            $menuService->updateCategory($category, $data);
            session()->flash('success', 'Category updated successfully.');
        } else {
            $menuService->createCategory($data);
            session()->flash('success', 'Category created successfully.');
        }

        $this->closeModal();
    }

    public function deleteCategory(int $id): void
    {
        $category = MenuCategory::findOrFail($id);
        app(MenuService::class)->deleteCategory($category);
        session()->flash('success', 'Category deleted successfully.');
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
        $this->icon = '';
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.category-management', [
            'categories' => MenuCategory::withCount('menus')->orderBy('sort_order')->get(),
        ]);
    }
}
