<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public function getMenusByCategory(?int $categoryId = null, ?string $search = null): Collection
    {
        $menus = Cache::rememberForever('pos_menus_all', function () {
            return Menu::query()
                ->with('category')
                ->where('is_available', true)
                ->orderBy('name')
                ->get();
        });

        if ($categoryId) {
            $menus = $menus->where('menu_category_id', $categoryId);
        }

        if ($search) {
            $menus = $menus->filter(function ($menu) use ($search) {
                return stripos($menu->name, $search) !== false;
            });
        }

        return $menus->values();
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

        $menu = Menu::create($data);
        Cache::forget('pos_menus_all');
        
        return $menu;
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
        Cache::forget('pos_menus_all');
        return $menu->fresh();
    }

    public function deleteMenu(Menu $menu): void
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();
        Cache::forget('pos_menus_all');
    }

    public function getCategories(): Collection
    {
        return Cache::rememberForever('pos_categories_all', function () {
            return MenuCategory::orderBy('sort_order')->get();
        });
    }

    public function createCategory(array $data): MenuCategory
    {
        $data['slug'] = Str::slug($data['name']);
        $category = MenuCategory::create($data);
        
        Cache::forget('pos_categories_all');
        Cache::forget('pos_menus_all');
        
        return $category;
    }

    public function updateCategory(MenuCategory $category, array $data): MenuCategory
    {
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        
        Cache::forget('pos_categories_all');
        Cache::forget('pos_menus_all');
        
        return $category->fresh();
    }

    public function deleteCategory(MenuCategory $category): void
    {
        $category->delete();
        Cache::forget('pos_categories_all');
        Cache::forget('pos_menus_all');
    }
}
