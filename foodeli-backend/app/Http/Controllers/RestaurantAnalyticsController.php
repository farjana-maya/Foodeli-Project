<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantAnalyticsController extends Controller
{
    public function getAnalytics()
    {
        try {
            $user = Auth::user();
            $restaurant = Restaurant::where('owner_id', $user->id)->first();
            
            if (!$restaurant) {
                return response()->json(['message' => 'Restaurant not found'], 404);
            }

            // Get analytics data
            $totalOrders = Order::where('restaurant_id', $restaurant->id)->count();
            $totalRevenue = $restaurant->total_earnings ?? 0;
            
            $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

            // Get top selling items from orders items field
            $topItems = collect();
            $orders = Order::where('restaurant_id', $restaurant->id)
                ->where('status', 'delivered')
                ->whereNotNull('items')
                ->get();
            
            $itemStats = [];
            foreach ($orders as $order) {
                if (is_array($order->items)) {
                    foreach ($order->items as $item) {
                        $menuItemId = $item['menu_item_id'] ?? null;
                        if ($menuItemId) {
                            $menuItem = \App\Models\MenuItem::find($menuItemId);
                            if ($menuItem) {
                                $key = $menuItem->name;
                                if (!isset($itemStats[$key])) {
                                    $itemStats[$key] = ['orders' => 0, 'revenue' => 0];
                                }
                                $itemStats[$key]['orders'] += $item['quantity'] ?? 1;
                                $itemStats[$key]['revenue'] += ($item['quantity'] ?? 1) * ($item['price'] ?? 0);
                            }
                        }
                    }
                }
            }
            
            $topItems = collect($itemStats)
                ->map(function($stats, $name) {
                    return [
                        'name' => $name,
                        'orders' => $stats['orders'],
                        'revenue' => number_format($stats['revenue'], 2)
                    ];
                })
                ->sortByDesc('orders')
                ->take(5)
                ->values();

            // Get recent orders with actual item count
            $recentOrders = Order::where('restaurant_id', $restaurant->id)
                ->with('customer:id,name')
                ->select('id', 'order_number', 'customer_id', 'total_amount', 'status', 'created_at', 'items')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($order) {
                    $itemsCount = 0;
                    if (is_array($order->items)) {
                        foreach ($order->items as $item) {
                            $itemsCount += $item['quantity'] ?? 1;
                        }
                    }
                    
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->customer->name ?? 'Unknown',
                        'items_count' => $itemsCount,
                        'total_amount' => $order->total_amount,
                        'status' => $order->status,
                        'created_at' => $order->created_at->setTimezone('Asia/Dhaka')
                    ];
                });

            // Get chart data - last 7 days orders
            $ordersData = [];
            $revenueData = [];
            $labels = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = now('Asia/Dhaka')->subDays($i);
                $dayOrders = Order::where('restaurant_id', $restaurant->id)
                    ->whereRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+06:00")) = ?', [$date->format('Y-m-d')])
                    ->count();
                $dayRevenue = Order::where('restaurant_id', $restaurant->id)
                    ->whereRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+06:00")) = ?', [$date->format('Y-m-d')])
                    ->sum('total_amount');
                    
                $ordersData[] = $dayOrders;
                $revenueData[] = (float) $dayRevenue;
                $labels[] = $date->format('M d');
            }

            // Calculate percentage changes (today vs yesterday)
            $todayOrders = Order::where('restaurant_id', $restaurant->id)
                ->whereRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+06:00")) = ?', [now('Asia/Dhaka')->format('Y-m-d')])
                ->count();
            $yesterdayOrders = Order::where('restaurant_id', $restaurant->id)
                ->whereRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+06:00")) = ?', [now('Asia/Dhaka')->subDay()->format('Y-m-d')])
                ->count();
            
            \Log::info('Orders Debug', [
                'today' => $todayOrders,
                'yesterday' => $yesterdayOrders,
                'today_date' => now()->toDateString(),
                'yesterday_date' => now()->subDay()->toDateString()
            ]);
            
            if ($yesterdayOrders == 0 && $todayOrders == 0) {
                $ordersChange = 0;
            } elseif ($yesterdayOrders == 0) {
                $ordersChange = 100;
            } else {
                $ordersChange = round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 1);
            }

            $todayRevenue = Order::where('restaurant_id', $restaurant->id)
                ->whereRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+06:00")) = ?', [now('Asia/Dhaka')->format('Y-m-d')])
                ->sum('total_amount');
            $yesterdayRevenue = Order::where('restaurant_id', $restaurant->id)
                ->whereRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+06:00")) = ?', [now('Asia/Dhaka')->subDay()->format('Y-m-d')])
                ->sum('total_amount');
                
            if ($yesterdayRevenue == 0 && $todayRevenue == 0) {
                $revenueChange = 0;
            } elseif ($yesterdayRevenue == 0) {
                $revenueChange = 100;
            } else {
                $revenueChange = round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1);
            }

            $todayAvg = $todayOrders > 0 ? $todayRevenue / $todayOrders : 0;
            $yesterdayAvg = $yesterdayOrders > 0 ? $yesterdayRevenue / $yesterdayOrders : 0;
            
            if ($yesterdayAvg == 0 && $todayAvg == 0) {
                $avgChange = 0;
            } elseif ($yesterdayAvg == 0) {
                $avgChange = 100;
            } else {
                $avgChange = round((($todayAvg - $yesterdayAvg) / $yesterdayAvg) * 100, 1);
            }

            return response()->json([
                'analytics' => [
                    'total_orders' => $totalOrders,
                    'total_revenue' => number_format($totalRevenue, 2),
                    'avg_order_value' => number_format($avgOrderValue, 2),
                    'rating' => number_format($restaurant->rating ?? 4.5, 1),
                    'orders_change' => $ordersChange,
                    'revenue_change' => $revenueChange,
                    'avg_change' => $avgChange,
                    'rating_change' => 0.2
                ],
                'top_items' => $topItems,
                'recent_orders' => $recentOrders,
                'chart_data' => [
                    'orders_labels' => $labels,
                    'orders_data' => $ordersData,
                    'revenue_labels' => $labels,
                    'revenue_data' => $revenueData
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error loading analytics'], 500);
        }
    }
}