<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RestaurantApplicationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $restaurant = Restaurant::create([
                'owner_id' => $request->owner_id,
                'name' => $request->name,
                'description' => $request->description,
                'cuisine_type' => $request->cuisine_type,
                'address' => $request->address,
                'city' => $request->city,
                'phone' => $request->phone,
                'email' => $request->email,
                'delivery_time' => $request->delivery_time,
                'delivery_fee' => $request->delivery_fee,
                'min_order_amount' => $request->min_order_amount,
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'is_active' => false,
                'status' => 'pending',
                'is_featured' => false,
                'is_verified' => false,
                'rating' => 0.00,
                'total_reviews' => 0
            ]);

            return response()->json([
                'success' => true,
                'restaurant' => $restaurant,
                'message' => 'Restaurant application submitted successfully. Please wait for admin approval.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPendingApplications()
    {
        try {
            $applications = Restaurant::where('is_active', false)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($app) {
                    $app->owner = (object)['name' => 'Restaurant Owner'];
                    return $app;
                });

            return response()->json([
                'success' => true,
                'applications' => $applications
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'applications' => []
            ]);
        }
    }

    public function approveApplication($id)
    {
        try {
            $restaurant = Restaurant::findOrFail($id);
            $restaurant->update(['is_active' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant application approved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function rejectApplication($id)
    {
        try {
            $restaurant = Restaurant::findOrFail($id);
            $owner = $restaurant->owner; // Get the owner before deleting restaurant

            // Mark as rejected instead of deleting
            $restaurant->update(['status' => 'rejected']);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant application rejected.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getApplicationStatus(Request $request)
    {
        try {
            $user = $request->user();
            $restaurant = Restaurant::where('owner_id', $user->id)->first();

            if (!$restaurant) {
                return response()->json([
                    'status' => 'no_application'
                ]);
            }

            if ($restaurant->is_active) {
                return response()->json([
                    'status' => 'approved'
                ]);
            } elseif ($restaurant->status === 'rejected') {
                return response()->json([
                    'status' => 'rejected'
                ]);
            } else {
                return response()->json([
                    'status' => 'pending'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
