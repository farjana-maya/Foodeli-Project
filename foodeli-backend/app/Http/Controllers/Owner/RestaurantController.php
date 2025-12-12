<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    // Get restaurant dashboard data
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $totalOrders = \App\Models\Order::where('restaurant_id', $restaurant->id)->count();
        
        $stats = [
            'totalOrders' => $totalOrders,
            'revenue' => 0,
            'menuItems' => $restaurant->menuItems()->count(),
            'rating' => $restaurant->rating
        ];

        return response()->json([
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'phone' => $restaurant->phone,
                'opening_time' => $restaurant->opening_time,
                'closing_time' => $restaurant->closing_time,
                'description' => $restaurant->description,
                'rating' => $restaurant->rating,
                'total_earnings' => $restaurant->total_earnings ?? 0,
                'owner_name' => $user->name
            ],
            'stats' => $stats
        ]);
    }

    // Get menu items
    public function getMenuItems(Request $request)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $menuItems = $restaurant->menuItems()->orderBy('created_at', 'desc')->get();

        return response()->json(['menuItems' => $menuItems]);
    }

    // Add menu item
    public function addMenuItem(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }
            
            $restaurant = Restaurant::where('owner_id', $user->id)->first();

            if (!$restaurant) {
                return response()->json(['message' => 'Restaurant not found'], 404);
            }

            // Only validate required fields to avoid database column issues
            $validated = $request->validate([
                'name' => 'required|string|max:500',
                'description' => 'nullable|string|max:2000',
                'price' => 'required|numeric|min:0',
                'category' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,svg|max:10240',
                'preparation_time' => 'nullable|integer|min:0'
            ]);

            $data = [
                'restaurant_id' => $restaurant->id,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'category' => $validated['category'] ?? 'main',
                'preparation_time' => $validated['preparation_time'] ?? 15,
                'is_available' => true,
                'is_featured' => false
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu_items', 'public');
                $data['image'] = basename($imagePath);
            }

            $menuItem = MenuItem::create($data);

            return response()->json([
                'message' => 'Menu item added successfully',
                'menuItem' => $menuItem
            ]);
        } catch (\Exception $e) {
            \Log::error('Menu item creation error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Error adding menu item',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    // Update menu item
    public function updateMenuItem(Request $request, $id)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $menuItem = MenuItem::where('restaurant_id', $restaurant->id)->findOrFail($id);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'preparation_time' => $request->preparation_time,
            'calories' => $request->calories,
            'spice_level' => $request->spice_level,
            'portion_size' => $request->portion_size,
            'cooking_method' => $request->cooking_method,
            'origin_country' => $request->origin_country,
            'is_available' => $request->is_available,
            'is_featured' => $request->is_featured
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($menuItem->image) {
                Storage::disk('public')->delete('menu_items/' . $menuItem->image);
            }
            
            $imagePath = $request->file('image')->store('menu_items', 'public');
            $data['image'] = basename($imagePath);
        }

        $menuItem->update($data);

        return response()->json([
            'message' => 'Menu item updated successfully',
            'menuItem' => $menuItem
        ]);
    }

    // Delete menu item
    public function deleteMenuItem(Request $request, $id)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $menuItem = MenuItem::where('restaurant_id', $restaurant->id)->findOrFail($id);

        // Delete image
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }

        $menuItem->delete();

        return response()->json(['message' => 'Menu item deleted successfully']);
    }

    // Update restaurant settings
    public function updateSettings(Request $request)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'delivery_fee' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0'
        ]);

        $restaurant->update($request->all());

        return response()->json([
            'message' => 'Restaurant settings updated successfully',
            'restaurant' => $restaurant
        ]);
    }

    // Get orders
    public function getOrders(Request $request)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $orders = \App\Models\Order::where('restaurant_id', $restaurant->id)
            ->with('customer:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['orders' => $orders]);
    }

    // Update order status
    public function updateOrderStatus(Request $request, $orderId)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,picked_up,delivered,cancelled'
        ]);

        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $order = \App\Models\Order::where('id', $orderId)
            ->where('restaurant_id', $restaurant->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Prevent status changes after delivered
        if ($order->status === 'delivered') {
            return response()->json(['message' => 'Cannot change status of delivered order'], 400);
        }

        // Don't assign rider automatically - let riders pick up orders manually
        // if ($validated['status'] === 'ready' && !$order->rider_id) {
        //     // Rider assignment will be handled when rider picks up the order
        // }

        $order->update([
            'status' => $validated['status'],
            'rider_id' => $order->rider_id
        ]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }
}