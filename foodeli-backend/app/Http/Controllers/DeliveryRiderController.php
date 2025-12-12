<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DeliveryRiderController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'date_of_birth' => 'required|date',
            'nid_number' => 'required|string|max:50',
            'driving_license' => 'required|string|max:50',
            'vehicle_type' => 'required|in:bike,bicycle',
            'vehicle_model' => 'required|string|max:100',
            'vehicle_number' => 'required|string|max:50',
            'profile_photo' => 'nullable|image|max:2048',
            'nid_photo' => 'nullable|image|max:2048',
            'license_photo' => 'nullable|image|max:2048',
            'vehicle_photo' => 'nullable|image|max:2048'
        ]);

        $data = $validated;
        $data['user_id'] = $request->user()->id;

        // Handle file uploads
        foreach (['profile_photo', 'nid_photo', 'license_photo', 'vehicle_photo'] as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('delivery_riders', 'public');
                $data[$field] = basename($path);
            }
        }

        $rider = DeliveryRider::create($data);

        return response()->json([
            'message' => 'Delivery rider registration submitted successfully',
            'rider' => $rider
        ]);
    }

    public function dashboard(Request $request)
    {
        $rider = DeliveryRider::where('user_id', $request->user()->id)->first();

        if (!$rider) {
            return response()->json(['message' => 'Rider not found'], 404);
        }

        // Get orders ready for pickup and picked up orders
        $currentOrders = \App\Models\Order::whereIn('status', ['ready', 'picked_up'])
            ->get();
            
        // Enrich with restaurant details
        foreach ($currentOrders as $order) {
            $restaurant = \DB::table('restaurants')->where('id', $order->restaurant_id)->first();
            $order->restaurant = $restaurant;
        }

        // Calculate today's orders
        $todaysOrders = \App\Models\Order::whereDate('delivered_at', today())
            ->where('status', 'delivered')
            ->count();

        return response()->json([
            'rider' => $rider,
            'current_orders' => $currentOrders,
            'stats' => [
                'total_deliveries' => $rider->total_deliveries ?? 0,
                'total_earnings' => $rider->total_earnings ?? 0,
                'todays_orders' => $todaysOrders,
                'rating' => $rider->rating ?? 0,
                'status' => $rider->status
            ]
        ]);
    }
    
    public function updateOrderStatus(Request $request, $orderId)
    {
        $validated = $request->validate([
            'status' => 'required|in:picked_up,delivered'
        ]);
        
        $rider = DeliveryRider::where('user_id', $request->user()->id)->first();
        if (!$rider) {
            return response()->json(['message' => 'Rider not found'], 404);
        }
        
        $order = \App\Models\Order::where('id', $orderId)->first();
            
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        // Update order status and set delivered_at if delivered
        if ($validated['status'] === 'delivered') {
            $order->update([
                'status' => $validated['status'],
                'delivered_at' => now()
            ]);
            $this->calculateEarnings($order, $rider);
        } else {
            $order->update(['status' => $validated['status']]);
        }
        
        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }
    
    private function calculateEarnings($order, $rider)
    {
        $deliveryFee = $order->delivery_fee;
        $totalAmount = $order->total_amount;
        $restaurantAmount = ($totalAmount - $deliveryFee) * 0.9;
        $adminAmount = ($totalAmount - $deliveryFee) * 0.1;
        
        // Add delivery fee to rider earnings and increment delivery count
        $rider->increment('total_earnings', $deliveryFee);
        $rider->increment('total_deliveries', 1);
        
        // Add restaurant earnings
        \DB::table('restaurants')
            ->where('id', $order->restaurant_id)
            ->increment('total_earnings', $restaurantAmount);
            
        // Add admin earnings to users table for admin role
        \DB::table('users')
            ->where('role', 'admin')
            ->increment('total_earnings', $adminAmount);
    }
}