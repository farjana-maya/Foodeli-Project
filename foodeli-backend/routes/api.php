<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Test route
Route::get('/test', function() {
    return response()->json(['message' => 'API working']);
});

// Simple test route for menu items
Route::middleware('auth:sanctum')->post('/test-menu', function(Request $request) {
    return response()->json([
        'message' => 'Test endpoint working',
        'user' => $request->user(),
        'data' => $request->all()
    ]);
});



// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/restaurant-applications', [App\Http\Controllers\RestaurantApplicationController::class, 'store']);
Route::get('/restaurants', [App\Http\Controllers\PublicController::class, 'getRestaurants']);
Route::get('/restaurants/{id}/menu', [App\Http\Controllers\PublicController::class, 'getRestaurantMenu']);

// Delivery Rider routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/delivery-rider/register', [App\Http\Controllers\DeliveryRiderController::class, 'register']);
    Route::get('/delivery-rider/dashboard', [App\Http\Controllers\DeliveryRiderController::class, 'dashboard']);
    Route::put('/delivery-rider/orders/{id}/status', [App\Http\Controllers\DeliveryRiderController::class, 'updateOrderStatus']);
});

// Order routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store']);
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index']);
    Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show']);
    Route::put('/orders/{id}/status', [App\Http\Controllers\OrderController::class, 'updateStatus']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar']);
    Route::get('/restaurant-application-status', [App\Http\Controllers\RestaurantApplicationController::class, 'getApplicationStatus']);
    
    // Restaurant Owner Routes (without middleware to prevent redirects)
    Route::prefix('owner')->middleware('auth:sanctum')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Owner\RestaurantController::class, 'dashboard']);
        Route::get('/menu-items', [App\Http\Controllers\Owner\RestaurantController::class, 'getMenuItems']);
        Route::post('/menu-items', [App\Http\Controllers\Owner\RestaurantController::class, 'addMenuItem']);
        Route::put('/menu-items/{id}', [App\Http\Controllers\Owner\RestaurantController::class, 'updateMenuItem']);
        Route::delete('/menu-items/{id}', [App\Http\Controllers\Owner\RestaurantController::class, 'deleteMenuItem']);
        Route::put('/settings', [App\Http\Controllers\Owner\RestaurantController::class, 'updateSettings']);
        Route::get('/orders', [App\Http\Controllers\Owner\RestaurantController::class, 'getOrders']);
        Route::put('/orders/{id}/status', [App\Http\Controllers\Owner\RestaurantController::class, 'updateOrderStatus']);
        
        // Enhanced Menu Management Routes - No Limitations
        Route::post('/menu-items/bulk', [App\Http\Controllers\Owner\MenuController::class, 'bulkAddMenuItems']);
        Route::post('/menu/import', [App\Http\Controllers\Owner\MenuController::class, 'importMenu']);
        Route::get('/menu/export', [App\Http\Controllers\Owner\MenuController::class, 'exportMenu']);
        Route::get('/menu/stats', [App\Http\Controllers\Owner\MenuController::class, 'getMenuStats']);
        Route::post('/menu-items/{id}/duplicate', [App\Http\Controllers\Owner\MenuController::class, 'duplicateMenuItem']);
    });
    
    // Restaurant Analytics
    Route::get('/restaurant/analytics', [App\Http\Controllers\RestaurantAnalyticsController::class, 'getAnalytics']);
    
    // Admin routes
    Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'stats']);

    // Users
    Route::get('/users', [DashboardController::class, 'users']);
    Route::post('/users/{id}/ban', [DashboardController::class, 'banUser']);
    Route::post('/users/{id}/unban', [DashboardController::class, 'unbanUser']);

    // Restaurants
    Route::get('/restaurants', [DashboardController::class, 'restaurants']);
    Route::get('/restaurant-applications', [App\Http\Controllers\RestaurantApplicationController::class, 'getPendingApplications']);
    Route::post('/restaurant-applications/{id}/approve', [App\Http\Controllers\RestaurantApplicationController::class, 'approveApplication']);
    Route::post('/restaurant-applications/{id}/reject', [App\Http\Controllers\RestaurantApplicationController::class, 'rejectApplication']);

    // Orders
    Route::get('/orders', [DashboardController::class, 'orders']);
    Route::put('/orders/{id}/status', [DashboardController::class, 'updateOrderStatus']);

    // Riders
    Route::get('/riders', [DashboardController::class, 'riders']);
    Route::get('/rider-applications', [DashboardController::class, 'getPendingRiderApplications']);
    Route::post('/riders/{id}/approve', [DashboardController::class, 'approveRider']);
    Route::post('/riders/{id}/reject', [DashboardController::class, 'rejectRider']);
});

});
