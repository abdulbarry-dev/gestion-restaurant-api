<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $tables = Table::all();
        $menuItems = MenuItem::all();

        // Order 1: Completed order for John Doe
        $order1 = Order::create([
            'customer_id' => $customers->where('name', 'John Doe')->first()->id,
            'table_id' => $tables->where('table_number', 'T2')->first()->id,
            'status' => 'completed',
            'payment_status' => 'paid',
            'notes' => 'Customer requested extra napkins',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'menu_item_id' => $menuItems->where('name', 'Caesar Salad')->first()->id,
            'quantity' => 1,
            'price' => 8.99,
            'subtotal' => 8.99,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'menu_item_id' => $menuItems->where('name', 'Grilled Salmon')->first()->id,
            'quantity' => 1,
            'price' => 22.99,
            'subtotal' => 22.99,
            'special_instructions' => 'Well done please',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'menu_item_id' => $menuItems->where('name', 'Coca Cola')->first()->id,
            'quantity' => 2,
            'price' => 2.50,
            'subtotal' => 5.00,
        ]);

        $order1->calculateTotals();

        // Order 2: In-progress order for Jane Smith
        $order2 = Order::create([
            'customer_id' => $customers->where('name', 'Jane Smith')->first()->id,
            'table_id' => $tables->where('table_number', 'T4')->first()->id,
            'status' => 'in-progress',
            'payment_status' => 'unpaid',
            'notes' => null,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'menu_item_id' => $menuItems->where('name', 'Margherita Pizza')->first()->id,
            'quantity' => 2,
            'price' => 13.99,
            'subtotal' => 27.98,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'menu_item_id' => $menuItems->where('name', 'Chicken Wings')->first()->id,
            'quantity' => 1,
            'price' => 10.99,
            'subtotal' => 10.99,
            'special_instructions' => 'Extra spicy',
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'menu_item_id' => $menuItems->where('name', 'Fresh Orange Juice')->first()->id,
            'quantity' => 2,
            'price' => 4.50,
            'subtotal' => 9.00,
        ]);

        $order2->calculateTotals();
        $order2->table->update(['status' => 'occupied']);

        // Order 3: Pending order for Bob Johnson
        $order3 = Order::create([
            'customer_id' => $customers->where('name', 'Bob Johnson')->first()->id,
            'table_id' => $tables->where('table_number', 'T1')->first()->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => 'Customer has a nut allergy',
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'menu_item_id' => $menuItems->where('name', 'Ribeye Steak')->first()->id,
            'quantity' => 1,
            'price' => 32.99,
            'subtotal' => 32.99,
            'special_instructions' => 'Medium rare',
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'menu_item_id' => $menuItems->where('name', 'Tiramisu')->first()->id,
            'quantity' => 1,
            'price' => 7.99,
            'subtotal' => 7.99,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'menu_item_id' => $menuItems->where('name', 'Espresso')->first()->id,
            'quantity' => 1,
            'price' => 3.50,
            'subtotal' => 3.50,
        ]);

        $order3->calculateTotals();
        $order3->table->update(['status' => 'occupied']);

        // Order 4: Large order for Alice Williams (family dinner)
        $order4 = Order::create([
            'customer_id' => $customers->where('name', 'Alice Williams')->first()->id,
            'table_id' => $tables->where('table_number', 'T7')->first()->id,
            'status' => 'in-progress',
            'payment_status' => 'unpaid',
            'notes' => 'Birthday celebration - bring candles',
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'menu_item_id' => $menuItems->where('name', 'Bruschetta')->first()->id,
            'quantity' => 3,
            'price' => 7.50,
            'subtotal' => 22.50,
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'menu_item_id' => $menuItems->where('name', 'Pepperoni Pizza')->first()->id,
            'quantity' => 2,
            'price' => 15.99,
            'subtotal' => 31.98,
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'menu_item_id' => $menuItems->where('name', 'Chicken Parmesan')->first()->id,
            'quantity' => 2,
            'price' => 18.99,
            'subtotal' => 37.98,
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'menu_item_id' => $menuItems->where('name', 'Vegetarian Pasta')->first()->id,
            'quantity' => 1,
            'price' => 15.99,
            'subtotal' => 15.99,
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'menu_item_id' => $menuItems->where('name', 'Chocolate Lava Cake')->first()->id,
            'quantity' => 4,
            'price' => 8.99,
            'subtotal' => 35.96,
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'menu_item_id' => $menuItems->where('name', 'Coca Cola')->first()->id,
            'quantity' => 4,
            'price' => 2.50,
            'subtotal' => 10.00,
        ]);

        $order4->calculateTotals();
        $order4->table->update(['status' => 'occupied']);

        // Order 5: Quick lunch for Charlie Brown
        $order5 = Order::create([
            'customer_id' => $customers->where('name', 'Charlie Brown')->first()->id,
            'table_id' => $tables->where('table_number', 'T5')->first()->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => 'Rush order - customer in a hurry',
        ]);

        OrderItem::create([
            'order_id' => $order5->id,
            'menu_item_id' => $menuItems->where('name', 'Caesar Salad')->first()->id,
            'quantity' => 1,
            'price' => 8.99,
            'subtotal' => 8.99,
        ]);

        OrderItem::create([
            'order_id' => $order5->id,
            'menu_item_id' => $menuItems->where('name', 'Quattro Formaggi')->first()->id,
            'quantity' => 1,
            'price' => 16.99,
            'subtotal' => 16.99,
        ]);

        OrderItem::create([
            'order_id' => $order5->id,
            'menu_item_id' => $menuItems->where('name', 'Fresh Orange Juice')->first()->id,
            'quantity' => 1,
            'price' => 4.50,
            'subtotal' => 4.50,
        ]);

        $order5->calculateTotals();
        $order5->table->update(['status' => 'occupied']);

        // Order 6: Previous completed order (for history)
        $order6 = Order::create([
            'customer_id' => $customers->where('name', 'Jane Smith')->first()->id,
            'table_id' => $tables->where('table_number', 'T3')->first()->id,
            'status' => 'completed',
            'payment_status' => 'paid',
            'notes' => null,
        ]);

        OrderItem::create([
            'order_id' => $order6->id,
            'menu_item_id' => $menuItems->where('name', 'Chicken Wings')->first()->id,
            'quantity' => 1,
            'price' => 10.99,
            'subtotal' => 10.99,
        ]);

        OrderItem::create([
            'order_id' => $order6->id,
            'menu_item_id' => $menuItems->where('name', 'Cheesecake')->first()->id,
            'quantity' => 1,
            'price' => 7.50,
            'subtotal' => 7.50,
        ]);

        $order6->calculateTotals();

        // Order 7: Cancelled order
        $order7 = Order::create([
            'customer_id' => $customers->where('name', 'Bob Johnson')->first()->id,
            'table_id' => $tables->where('table_number', 'T6')->first()->id,
            'status' => 'cancelled',
            'payment_status' => 'refunded',
            'notes' => 'Customer had to leave unexpectedly',
        ]);

        OrderItem::create([
            'order_id' => $order7->id,
            'menu_item_id' => $menuItems->where('name', 'Grilled Salmon')->first()->id,
            'quantity' => 1,
            'price' => 22.99,
            'subtotal' => 22.99,
        ]);

        $order7->calculateTotals();
    }
}
