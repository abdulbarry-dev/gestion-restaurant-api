<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['customer', 'table', 'orderItems.menuItem']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by table
        if ($request->has('table_id')) {
            $query->where('table_id', $request->table_id);
        }

        $orders = $query->latest()->paginate(15);

        return response()->json($orders);
    }

    /**
     * Store a newly created order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'table_id' => $request->table_id,
            'notes' => $request->notes,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $menuItem = MenuItem::findOrFail($item['menu_item_id']);
            
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $menuItem->id,
                'quantity' => $item['quantity'],
                'price' => $menuItem->price,
                'subtotal' => $menuItem->price * $item['quantity'],
                'special_instructions' => $item['special_instructions'] ?? null,
            ]);
        }

        // Calculate totals
        $order->calculateTotals();

        // Update table status to occupied
        if ($order->table_id && $order->table()) {
            $order->table()->update(['status' => 'occupied']);
        }

        return response()->json($order->load(['customer', 'table', 'orderItems.menuItem']), 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        return response()->json($order->load(['customer', 'table', 'orderItems.menuItem']));
    }

    /**
     * Update the specified order.
     */
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        $order->update($request->only([
            'customer_id',
            'table_id',
            'status',
            'payment_status',
            'notes',
        ]));

        // Update order items if provided
        if ($request->has('items')) {
            // Delete existing items
            $order->orderItems()->delete();

            // Create new items
            foreach ($request->items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price,
                    'subtotal' => $menuItem->price * $item['quantity'],
                    'special_instructions' => $item['special_instructions'] ?? null,
                ]);
            }

            // Recalculate totals
            $order->calculateTotals();
        }

        // Update table status
        if ($order->status === 'completed' && $order->table_id && $order->table()) {
            $order->table()->update(['status' => 'available']);
        }

        return response()->json($order->load(['customer', 'table', 'orderItems.menuItem']));
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order): JsonResponse
    {
        // Free up the table if occupied
        if ($order->table_id && $order->table()) {
            $order->table()->update(['status' => 'available']);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
