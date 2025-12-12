<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function getRestaurants()
    {
        $restaurants = Restaurant::where('is_active', true)
            ->with('owner:id,name')
            ->select('id', 'name', 'description', 'phone', 'address', 'city', 'cuisine_type', 'rating', 'delivery_fee', 'min_order_amount', 'opening_time', 'closing_time', 'cover_image', 'logo', 'delivery_time', 'owner_id')
            ->get()
            ->map(function ($restaurant) {
                $restaurant->image = $restaurant->cover_image ? 
                    'http://127.0.0.1:8000/uploads/restaurants/' . $restaurant->cover_image : 
                    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500&h=300&fit=crop';
                
                $restaurant->logoUrl = null;
                
                $restaurant->location = $restaurant->address . ($restaurant->city ? ', ' . $restaurant->city : '');
                $restaurant->deliveryTime = $restaurant->delivery_time . ' min';
                $restaurant->deliveryFee = $restaurant->delivery_fee == 0 ? 'Free' : '$' . $restaurant->delivery_fee;
                $restaurant->openingHours = date('g:i A', strtotime($restaurant->opening_time)) . ' - ' . date('g:i A', strtotime($restaurant->closing_time));
                
                return $restaurant;
            });

        return response()->json(['restaurants' => $restaurants]);
    }

    public function getRestaurantMenu($id)
    {
        $restaurant = Restaurant::where('id', $id)
            ->where('is_active', true)
            ->with('owner:id,name')
            ->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        // Add image URLs to restaurant
        $restaurant->image = $restaurant->cover_image ? 
            'http://127.0.0.1:8000/uploads/restaurants/' . $restaurant->cover_image : 
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&h=400&fit=crop';
        
        $restaurant->logoUrl = null;

        $menuItems = MenuItem::where('restaurant_id', $id)
            ->where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return response()->json([
            'restaurant' => $restaurant,
            'menuItems' => $menuItems
        ]);
    }
}