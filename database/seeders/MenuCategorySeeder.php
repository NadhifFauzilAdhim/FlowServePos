<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hot Coffee', 'slug' => 'hot-coffee', 'icon' => 'coffee', 'sort_order' => 1],
            ['name' => 'Cold Drinks', 'slug' => 'cold-drinks', 'icon' => 'local_bar', 'sort_order' => 2],
            ['name' => 'Pastries', 'slug' => 'pastries', 'icon' => 'bakery_dining', 'sort_order' => 3],
            ['name' => 'Sandwiches', 'slug' => 'sandwiches', 'icon' => 'lunch_dining', 'sort_order' => 4],
            ['name' => 'Desserts', 'slug' => 'desserts', 'icon' => 'icecream', 'sort_order' => 5],
            ['name' => 'Non-Coffee', 'slug' => 'non-coffee', 'icon' => 'emoji_food_beverage', 'sort_order' => 6],
        ];

        foreach ($categories as $category) {
            MenuCategory::create($category);
        }
    }
}
