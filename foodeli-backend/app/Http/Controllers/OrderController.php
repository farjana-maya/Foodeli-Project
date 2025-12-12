<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'restaurant_id' => 'required|exists:restaurants,id',
                'items' => 'required|array',
                'items.*.menu_item_id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'delivery_address' => 'required|string',
                'customer_phone' => 'required|string',
                'subtotal' => 'required|numeric|min:0',
                'delivery_fee' => 'required|numeric|min:0',
                'total_amount' => 'required|numeric|min:0'
            ]);

            $order = Order::create([
                'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
                'customer_id' => $request->user()->id,
                'restaurant_id' => $validated['restaurant_id'],
                'items' => $validated['items'],
                'subtotal' => $validated['subtotal'],
                'delivery_fee' => $validated['delivery_fee'],
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $validated['total_amount'],
                'delivery_address' => [$validated['delivery_address']],
                'customer_phone' => $validated['customer_phone'],
                'status' => 'pending',
                'payment_method' => 'cash',
                'payment_status' => 'pending'
            ]);

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Order validation error: ' . json_encode($e->errors()));
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Order creation error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Error creating order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $orders = Order::where('customer_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($orders as $order) {
                if (is_array($order->items)) {
                    $enrichedItems = [];
                    foreach ($order->items as $item) {
                        $menuItem = \DB::table('menu_items')
                            ->select('name', 'image')
                            ->where('id', $item['menu_item_id'])
                            ->first();
                        
                        $enrichedItem = $item;
                        if ($menuItem) {
                            $enrichedItem['name'] = $menuItem->name;
                            $enrichedItem['image'] = $menuItem->image;
                        }
                        $enrichedItems[] = $enrichedItem;
                    }
                    $order->items = $enrichedItems;
                }
            }

            return response()->json(['orders' => $orders]);
        } catch (\Exception $e) {
            \Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id, Request $request)
    {
        $order = Order::where('id', $id)
            ->where('customer_id', $request->user()->id)
            ->with('restaurant:id,name')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(['order' => $order]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,picked_up,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        
        // If status is being set to delivered, calculate earnings
        if ($validated['status'] === 'delivered' && $order->status !== 'delivered') {
            $this->calculateEarnings($order);
        }
        
        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }
    
    private function calculateEarnings($order)
    {
        $deliveryFee = $order->delivery_fee;
        $totalAmount = $order->total_amount;
        $restaurantAmount = ($totalAmount - $deliveryFee) * 0.9;
        $adminAmount = ($totalAmount - $deliveryFee) * 0.1;
        
        // Add delivery fee to rider earnings
        if ($order->rider_id) {
            \DB::table('delivery_riders')
                ->where('id', $order->rider_id)
                ->increment('total_earnings', $deliveryFee);
        }
        
        // Add restaurant earnings
        \DB::table('restaurants')
            ->where('id', $order->restaurant_id)
            ->increment('total_earnings', $restaurantAmount);
            
        // Add admin earnings
        \DB::table('users')
            ->where('role', 'admin')
            ->increment('total_earnings', $adminAmount);
    }
}