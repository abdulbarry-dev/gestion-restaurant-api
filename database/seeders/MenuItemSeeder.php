<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuItems = [
            // Appetizers
            [
                'name' => 'Caesar Salad',
                'description' => 'Fresh romaine lettuce with Caesar dressing, croutons, and parmesan cheese',
                'price' => 8.99,
                'category' => 'Appetizers',
                'is_available' => true,
            ],
            [
                'name' => 'Bruschetta',
                'description' => 'Toasted bread topped with tomatoes, garlic, basil, and olive oil',
                'price' => 7.50,
                'category' => 'Appetizers',
                'is_available' => true,
            ],
            [
                'name' => 'Chicken Wings',
                'description' => 'Crispy chicken wings with buffalo or BBQ sauce',
                'price' => 10.99,
                'category' => 'Appetizers',
                'is_available' => true,
            ],
            
            // Main Courses
            [
                'name' => 'Grilled Salmon',
                'description' => 'Fresh Atlantic salmon with lemon butter sauce, served with vegetables',
                'price' => 22.99,
                'category' => 'Main Courses',
                'is_available' => true,
            ],
            [
                'name' => 'Ribeye Steak',
                'description' => '12oz premium ribeye steak, cooked to perfection with garlic butter',
                'price' => 32.99,
                'category' => 'Main Courses',
                'is_available' => true,
            ],
            [
                'name' => 'Chicken Parmesan',
                'description' => 'Breaded chicken breast with marinara sauce and melted mozzarella',
                'price' => 18.99,
                'category' => 'Main Courses',
                'is_available' => true,
            ],
            [
                'name' => 'Vegetarian Pasta',
                'description' => 'Penne pasta with seasonal vegetables in a light tomato sauce',
                'price' => 15.99,
                'category' => 'Main Courses',
                'is_available' => true,
            ],
            
            // Pizzas
            [
                'name' => 'Margherita Pizza',
                'description' => 'Classic pizza with tomato sauce, mozzarella, and fresh basil',
                'price' => 13.99,
                'category' => 'Pizzas',
                'is_available' => true,
            ],
            [
                'name' => 'Pepperoni Pizza',
                'description' => 'Loaded with pepperoni and extra cheese',
                'price' => 15.99,
                'category' => 'Pizzas',
                'is_available' => true,
            ],
            [
                'name' => 'Quattro Formaggi',
                'description' => 'Four cheese pizza with mozzarella, gorgonzola, parmesan, and ricotta',
                'price' => 16.99,
                'category' => 'Pizzas',
                'is_available' => true,
            ],
            
            // Desserts
            [
                'name' => 'Tiramisu',
                'description' => 'Classic Italian dessert with coffee-soaked ladyfingers and mascarpone',
                'price' => 7.99,
                'category' => 'Desserts',
                'is_available' => true,
            ],
            [
                'name' => 'Chocolate Lava Cake',
                'description' => 'Warm chocolate cake with a molten center, served with vanilla ice cream',
                'price' => 8.99,
                'category' => 'Desserts',
                'is_available' => true,
            ],
            [
                'name' => 'Cheesecake',
                'description' => 'New York style cheesecake with berry compote',
                'price' => 7.50,
                'category' => 'Desserts',
                'is_available' => true,
            ],
            
            // Beverages
            [
                'name' => 'Coca Cola',
                'description' => 'Classic Coca Cola (330ml)',
                'price' => 2.50,
                'category' => 'Beverages',
                'is_available' => true,
            ],
            [
                'name' => 'Fresh Orange Juice',
                'description' => 'Freshly squeezed orange juice',
                'price' => 4.50,
                'category' => 'Beverages',
                'is_available' => true,
            ],
            [
                'name' => 'Espresso',
                'description' => 'Double shot of premium espresso',
                'price' => 3.50,
                'category' => 'Beverages',
                'is_available' => true,
            ],
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }
    }
}
