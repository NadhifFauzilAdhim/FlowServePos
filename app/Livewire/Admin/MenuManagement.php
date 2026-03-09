<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Setting;
use App\Services\MenuService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class MenuManagement extends Component
{
    use WithFileUploads;

    public string $search = '';

    public bool $showModal = false;

    public bool $showTaxModal = false;

    public float $taxRate = 8.00;

    public ?int $editingId = null;

    // Form fields
    public string $name = '';

    public ?int $menu_category_id = null;

    public string $description = '';

    public float $price = 0;

    public $image = null;

    public bool $is_available = true;

    protected function rules(): array
    {
        return [
            'name' => 'required|min:2|max:255',
            'menu_category_id' => 'required|exists:menu_categories,id',
            'description' => 'nullable|max:500',
            'price' => 'required|numeric|min:0',
            'image' => $this->editingId ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openTaxModal(): void
    {
        $this->taxRate = (float) Setting::get('tax_rate', 8.00);
        $this->showTaxModal = true;
    }

    public function closeTaxModal(): void
    {
        $this->showTaxModal = false;
    }

    public function saveTaxRate(): void
    {
        $this->validate([
            'taxRate' => 'required|numeric|min:0|max:100',
        ]);

        Setting::set('tax_rate', $this->taxRate);

        session()->flash('success', 'Tax rate updated successfully.');
        $this->closeTaxModal();
    }

    public function openEditModal(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $this->editingId = $menu->id;
        $this->name = $menu->name;
        $this->menu_category_id = $menu->menu_category_id;
        $this->description = $menu->description ?? '';
        $this->price = (float) $menu->price;
        $this->is_available = $menu->is_available;
        $this->image = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $menuService = app(MenuService::class);
        $data = [
            'name' => $this->name,
            'menu_category_id' => $this->menu_category_id,
            'description' => $this->description,
            'price' => $this->price,
            'is_available' => $this->is_available,
        ];

        if ($this->image) {
            $data['image'] = $this->image;
        }

        if ($this->editingId) {
            $menu = Menu::findOrFail($this->editingId);
            $menuService->updateMenu($menu, $data);
            session()->flash('success', 'Menu item updated successfully.');
        } else {
            $menuService->createMenu($data);
            session()->flash('success', 'Menu item created successfully.');
        }

        $this->closeModal();
    }

    public function deleteMenu(int $id): void
    {
        $menu = Menu::findOrFail($id);
        app(MenuService::class)->deleteMenu($menu);
        session()->flash('success', 'Menu item deleted successfully.');
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
        $this->menu_category_id = null;
        $this->description = '';
        $this->price = 0;
        $this->image = null;
        $this->is_available = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.menu-management', [
            'menus' => app(MenuService::class)->getAllMenus($this->search ?: null),
            'categories' => MenuCategory::orderBy('sort_order')->get(),
        ]);
    }
}
