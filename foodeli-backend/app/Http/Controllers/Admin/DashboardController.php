<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\DeliveryRider;

class DashboardController extends Controller
{
    // GET /api/admin/dashboard/stats
    public function stats()
    {
        try {
            $today = now()->startOfDay();
            $yesterday = now()->subDay()->startOfDay();
            
            $totalUsers = User::count();
            $totalRestaurants = Restaurant::count();
            $totalOrders = Order::count();
            $adminUser = User::where('role', 'admin')->first();
            $revenue = $adminUser ? $adminUser->total_earnings : 0;
            
            // Calculate daily changes
            $usersToday = User::whereDate('created_at', $today)->count();
            $usersYesterday = User::whereDate('created_at', $yesterday)->count();
            $userChange = $usersYesterday > 0 ? (($usersToday - $usersYesterday) / $usersYesterday) * 100 : ($usersToday > 0 ? 100 : 0);
            
            $restaurantsToday = Restaurant::whereDate('created_at', $today)->count();
            $restaurantsYesterday = Restaurant::whereDate('created_at', $yesterday)->count();
            $restaurantChange = $restaurantsYesterday > 0 ? (($restaurantsToday - $restaurantsYesterday) / $restaurantsYesterday) * 100 : ($restaurantsToday > 0 ? 100 : 0);
            
            $ordersToday = Order::whereDate('created_at', $today)->count();
            $ordersYesterday = Order::whereDate('created_at', $yesterday)->count();
            $orderChange = $ordersYesterday > 0 ? (($ordersToday - $ordersYesterday) / $ordersYesterday) * 100 : ($ordersToday > 0 ? 100 : 0);
            
            $revenueToday = Order::whereDate('created_at', $today)->sum('total_amount') * 0.1;
            $revenueYesterday = Order::whereDate('created_at', $yesterday)->sum('total_amount') * 0.1;
            $revenueChange = $revenueYesterday > 0 ? (($revenueToday - $revenueYesterday) / $revenueYesterday) * 100 : ($revenueToday > 0 ? 100 : 0);

            // Get top restaurants by order count
            $topRestaurants = Restaurant::select('id', 'name', 'cuisine_type', 'rating')
                ->withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function($restaurant) {
                    return [
                        'id' => $restaurant->id,
                        'name' => $restaurant->name,
                        'cuisine' => $restaurant->cuisine_type ?? 'Not specified',
                        'rating' => number_format($restaurant->rating ?? 4.5, 1),
                        'orders' => $restaurant->orders_count
                    ];
                });

            // Get recent orders
            $recentOrders = Order::with(['customer:id,name', 'restaurant:id,name'])
                ->select('id', 'order_number', 'customer_id', 'restaurant_id', 'total_amount', 'status')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'orderNumber' => $order->order_number,
                        'customer' => $order->customer->name ?? 'Unknown',
                        'restaurant' => $order->restaurant->name ?? 'Unknown',
                        'amount' => number_format($order->total_amount, 2),
                        'status' => $order->status
                    ];
                });

            return response()->json([
                'totalUsers' => $totalUsers,
                'totalRestaurants' => $totalRestaurants,
                'totalOrders' => $totalOrders,
                'revenue' => $revenue ?? 0,
                'userChange' => round($userChange, 1),
                'restaurantChange' => round($restaurantChange, 1),
                'orderChange' => round($orderChange, 1),
                'revenueChange' => round($revenueChange, 1),
                'topRestaurants' => $topRestaurants,
                'recentOrders' => $recentOrders
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard stats error: ' . $e->getMessage());
            return response()->json([
                'totalUsers' => 0,
                'totalRestaurants' => 0,
                'totalOrders' => 0,
                'revenue' => 0,
                'topRestaurants' => [],
                'recentOrders' => []
            ]);
        }
    }

    // GET /api/admin/dashboard/restaurants
    public function restaurants()
    {
        try {
            $restaurants = Restaurant::select('id', 'name', 'cuisine_type', 'rating', 'is_active')
                ->get()
                ->map(function($restaurant) {
                    return [
                        'id' => $restaurant->id,
                        'name' => $restaurant->name,
                        'owner' => 'Restaurant Owner',
                        'cuisine' => $restaurant->cuisine_type ?? 'Not specified',
                        'status' => $restaurant->is_active ? 'Active' : 'Inactive',
                        'rating' => $restaurant->rating ?? 0,
                        'orders' => Order::where('restaurant_id', $restaurant->id)->count()
                    ];
                });
            return response()->json($restaurants);
        } catch (\Exception $e) {
            \Log::error('Dashboard restaurants error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    // GET /api/admin/dashboard/orders
    public function orders()
    {
        try {
            $orders = Order::with(['customer:id,name', 'restaurant:id,name'])
                ->select('id', 'order_number', 'customer_id', 'restaurant_id', 'total_amount', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer' => $order->customer->name ?? 'Unknown',
                        'restaurant' => $order->restaurant->name ?? 'Unknown',
                        'amount' => $order->total_amount,
                        'status' => $order->status,
                        'date' => $order->created_at->format('Y-m-d H:i')
                    ];
                });
            return response()->json($orders);
        } catch (\Exception $e) {
            \Log::error('Dashboard orders error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    // GET /api/admin/dashboard/users
    public function users()
    {
        try {
            $users = User::select('id', 'name', 'email', 'role', 'created_at')
                ->get()
                ->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role ?? 'customer',
                        'status' => 'Active',
                        'orders' => Order::where('customer_id', $user->id)->count(),
                        'joined' => $user->created_at->format('Y-m-d')
                    ];
                });
            return response()->json($users);
        } catch (\Exception $e) {
            \Log::error('Dashboard users error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    // GET /api/admin/dashboard/riders
    public function riders()
    {
        try {
            $riders = DeliveryRider::with('user:id,name,email')
                ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($rider) {
                    return [
                        'id' => $rider->id,
                        'name' => $rider->full_name,
                        'email' => $rider->email,
                        'phone' => $rider->phone,
                        'vehicle_type' => $rider->vehicle_type,
                        'vehicle_model' => $rider->vehicle_model,
                        'vehicle_number' => $rider->vehicle_number,
                        'status' => $rider->status,
                        'rating' => $rider->rating,
                        'total_deliveries' => $rider->total_deliveries,
                        'created_at' => $rider->created_at->format('Y-m-d H:i')
                    ];
                });
            return response()->json($riders);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function approveRider($id)
    {
        try {
            $rider = DeliveryRider::findOrFail($id);
            $rider->update(['status' => 'approved']);
            
            return response()->json(['message' => 'Rider approved successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to approve rider'], 500);
        }
    }

    public function rejectRider($id)
    {
        try {
            $rider = DeliveryRider::findOrFail($id);
            $rider->update(['status' => 'rejected']);
            
            return response()->json(['message' => 'Rider rejected']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to reject rider'], 500);
        }
    }

    public function getPendingRiderApplications()
    {
        try {
            $applications = DeliveryRider::where('status', 'pending')
                ->with('user:id,name,email')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($rider) {
                    return [
                        'id' => $rider->id,
                        'name' => $rider->full_name,
                        'email' => $rider->email,
                        'phone' => $rider->phone,
                        'vehicle_type' => $rider->vehicle_type,
                        'vehicle_model' => $rider->vehicle_model,
                        'vehicle_number' => $rider->vehicle_number,
                        'created_at' => $rider->created_at->format('Y-m-d H:i')
                    ];
                });
            return response()->json($applications);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
}
