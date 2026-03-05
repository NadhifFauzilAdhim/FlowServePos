<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MenuService
{
    public function getMenusByCategory(?int $categoryId = null, ?string $search = null): Collection
    {
        return Menu::query()
            ->with('category')
            ->when($categoryId, fn($q) => $q->where('menu_category_id', $categoryId))
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->where('is_available', true)
            ->orderBy('name')
            ->get();
    }

    public function getAllMenus(?string $search = null): Collection
    {
        return Menu::query()
            ->with('category')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->get();
    }

    public function createMenu(array $data): Menu
    {
        $data['slug'] = Str::slug($data['name']);

        if (isset($data['image']) && $data['image']) {
            $data['image'] = $data['image']->store('menus', 'public');
        }

        return Menu::create($data);
    }

    public function updateMenu(Menu $menu, array $data): Menu
    {
        $data['slug'] = Str::slug($data['name']);

        if (isset($data['image']) && $data['image']) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $data['image'] = $data['image']->store('menus', 'public');
        } else {
            unset($data['image']);
        }

        $menu->update($data);
        return $menu->fresh();
    }

    public function deleteMenu(Menu $menu): void
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();
    }

    public function getCategories(): Collection
    {
        return MenuCategory::orderBy('sort_order')->get();
    }

    public function createCategory(array $data): MenuCategory
    {
        $data['slug'] = Str::slug($data['name']);
        return MenuCategory::create($data);
    }

    public function updateCategory(MenuCategory $category, array $data): MenuCategory
    {
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        return $category->fresh();
    }

    public function deleteCategory(MenuCategory $category): void
    {
        $category->delete();
    }
}
