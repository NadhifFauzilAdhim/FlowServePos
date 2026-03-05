<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = MenuCategory::all()->keyBy('slug');

        $menus = [
            // Hot Coffee
            ['menu_category_id' => $categories['hot-coffee']->id, 'name' => 'Espresso', 'slug' => 'espresso', 'description' => 'Double shot of rich espresso', 'price' => 25000],
            ['menu_category_id' => $categories['hot-coffee']->id, 'name' => 'Cappuccino', 'slug' => 'cappuccino', 'description' => 'Espresso with steamed milk foam', 'price' => 35000],
            ['menu_category_id' => $categories['hot-coffee']->id, 'name' => 'Caffe Latte', 'slug' => 'caffe-latte', 'description' => 'Espresso with steamed milk', 'price' => 35000],
            ['menu_category_id' => $categories['hot-coffee']->id, 'name' => 'Americano', 'slug' => 'americano', 'description' => 'Espresso diluted with hot water', 'price' => 28000],
            ['menu_category_id' => $categories['hot-coffee']->id, 'name' => 'Mocha', 'slug' => 'mocha', 'description' => 'Espresso with chocolate and steamed milk', 'price' => 38000],

            // Cold Drinks
            ['menu_category_id' => $categories['cold-drinks']->id, 'name' => 'Iced Latte', 'slug' => 'iced-latte', 'description' => 'Chilled espresso with milk over ice', 'price' => 38000],
            ['menu_category_id' => $categories['cold-drinks']->id, 'name' => 'Cold Brew', 'slug' => 'cold-brew', 'description' => '12-hour steeped cold coffee', 'price' => 35000],
            ['menu_category_id' => $categories['cold-drinks']->id, 'name' => 'Iced Americano', 'slug' => 'iced-americano', 'description' => 'Espresso with cold water and ice', 'price' => 30000],
            ['menu_category_id' => $categories['cold-drinks']->id, 'name' => 'Iced Mocha', 'slug' => 'iced-mocha', 'description' => 'Chocolate espresso over ice', 'price' => 40000],

            // Pastries
            ['menu_category_id' => $categories['pastries']->id, 'name' => 'Butter Croissant', 'slug' => 'butter-croissant', 'description' => 'Flaky, buttery French pastry', 'price' => 28000],
            ['menu_category_id' => $categories['pastries']->id, 'name' => 'Chocolate Muffin', 'slug' => 'chocolate-muffin', 'description' => 'Rich double chocolate muffin', 'price' => 25000],
            ['menu_category_id' => $categories['pastries']->id, 'name' => 'Cinnamon Roll', 'slug' => 'cinnamon-roll', 'description' => 'Soft roll with cinnamon sugar glaze', 'price' => 30000],

            // Sandwiches
            ['menu_category_id' => $categories['sandwiches']->id, 'name' => 'Avocado Toast', 'slug' => 'avocado-toast', 'description' => 'Sourdough with smashed avocado', 'price' => 45000],
            ['menu_category_id' => $categories['sandwiches']->id, 'name' => 'Grilled Cheese', 'slug' => 'grilled-cheese', 'description' => 'Classic grilled cheese sandwich', 'price' => 35000],
            ['menu_category_id' => $categories['sandwiches']->id, 'name' => 'Club Sandwich', 'slug' => 'club-sandwich', 'description' => 'Triple layer with chicken and bacon', 'price' => 50000],

            // Desserts
            ['menu_category_id' => $categories['desserts']->id, 'name' => 'Tiramisu', 'slug' => 'tiramisu', 'description' => 'Classic Italian coffee dessert', 'price' => 42000],
            ['menu_category_id' => $categories['desserts']->id, 'name' => 'Cheesecake', 'slug' => 'cheesecake', 'description' => 'New York style cheesecake', 'price' => 40000],

            // Non-Coffee
            ['menu_category_id' => $categories['non-coffee']->id, 'name' => 'Matcha Latte', 'slug' => 'matcha-latte', 'description' => 'Japanese ceremonial grade matcha', 'price' => 38000],
            ['menu_category_id' => $categories['non-coffee']->id, 'name' => 'Hot Chocolate', 'slug' => 'hot-chocolate', 'description' => 'Rich Belgian hot chocolate', 'price' => 35000],
            ['menu_category_id' => $categories['non-coffee']->id, 'name' => 'Fresh Orange Juice', 'slug' => 'fresh-orange-juice', 'description' => 'Freshly squeezed oranges', 'price' => 30000],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
