<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_order_amount',
        'usage_limit',
        'usage_per_user',
        'used_count',
        'valid_from',
        'valid_until',
        'applicable_restaurants',
        'applicable_categories',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_per_user' => 'integer',
        'used_count' => 'integer',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'applicable_restaurants' => 'array',
        'applicable_categories' => 'array',
        'is_active' => 'boolean',
    ];

    // Constants
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
                     ->where(function($q) {
                         $q->whereNull('valid_from')
                           ->orWhere('valid_from', '<=', now());
                     })
                     ->where(function($q) {
                         $q->whereNull('valid_until')
                           ->orWhere('valid_until', '>=', now());
                     })
                     ->where(function($q) {
                         $q->whereNull('usage_limit')
                           ->orWhereRaw('used_count < usage_limit');
                     });
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper($code));
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getFormattedDiscountAttribute()
    {
        if ($this->discount_type === self::TYPE_PERCENTAGE) {
            return $this->discount_value . '%';
        }
        return '৳' . number_format($this->discount_value, 2);
    }

    public function getIsValidAttribute()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->valid_from && $this->valid_from->isFuture()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function getIsExpiredAttribute()
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    public function getUsageRemainingAttribute()
    {
        if (!$this->usage_limit) {
            return 'Unlimited';
        }
        return max(0, $this->usage_limit - $this->used_count);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function canBeUsedBy($userId, $orderAmount, $restaurantId = null)
    {
        // Check if coupon is valid
        if (!$this->is_valid) {
            return ['valid' => false, 'message' => 'Coupon is not valid or has expired'];
        }

        // Check minimum order amount
        if ($orderAmount < $this->min_order_amount) {
            return [
                'valid' => false, 
                'message' => "Minimum order amount is ৳" . number_format($this->min_order_amount, 2)
            ];
        }

        // Check restaurant restriction
        if (!empty($this->applicable_restaurants) && $restaurantId) {
            if (!in_array($restaurantId, $this->applicable_restaurants)) {
                return ['valid' => false, 'message' => 'Coupon not applicable for this restaurant'];
            }
        }

        // Check per-user usage limit
        // You'll need to implement a coupon_usage table to track this properly
        // This is a simplified version
        
        return ['valid' => true, 'message' => 'Coupon can be applied'];
    }

    public function calculateDiscount($orderAmount)
    {
        if ($this->discount_type === self::TYPE_PERCENTAGE) {
            $discount = ($orderAmount * $this->discount_value) / 100;
            
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            
            return $discount;
        }

        return min($this->discount_value, $orderAmount);
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }
}
