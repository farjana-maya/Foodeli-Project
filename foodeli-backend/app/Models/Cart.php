<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'menu_id',
        'restaurant_id',
        'quantity',
        'variations',
        'special_instructions',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'variations' => 'array',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeForRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getItemTotalAttribute()
    {
        return $this->quantity * $this->menu->final_price;
    }

    public function getFormattedItemTotalAttribute()
    {
        return '৳' . number_format($this->item_total, 2);
    }

    public function getHasVariationsAttribute()
    {
        return !empty($this->variations);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function updateQuantity($quantity)
    {
        $this->update(['quantity' => max(1, $quantity)]);
    }

    public function incrementQuantity($amount = 1)
    {
        $this->increment('quantity', $amount);
    }

    public function decrementQuantity($amount = 1)
    {
        if ($this->quantity > $amount) {
            $this->decrement('quantity', $amount);
        } else {
            $this->delete();
        }
    }

    // ============================================
    // STATIC HELPER METHODS
    // ============================================

    public static function getCartTotal($userId)
    {
        return static::where('user_id', $userId)
            ->with('menu')
            ->get()
            ->sum(function($item) {
                return $item->quantity * $item->menu->final_price;
            });
    }

    public static function getCartItemCount($userId)
    {
        return static::where('user_id', $userId)->sum('quantity');
    }

    public static function clearCart($userId)
    {
        return static::where('user_id', $userId)->delete();
    }
}