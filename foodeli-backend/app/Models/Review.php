<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'order_id',
        'rating',
        'comment',
        'rider_rating',
        'rider_comment',
        'is_approved',
        'is_featured',
        'admin_response',
        'admin_responded_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'rider_rating' => 'integer',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'admin_responded_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeMinRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeWithResponse($query)
    {
        return $query->whereNotNull('admin_response');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getRatingStarsAttribute()
    {
        return str_repeat('⭐', $this->rating);
    }

    public function getRiderRatingStarsAttribute()
    {
        if (!$this->rider_rating) {
            return null;
        }
        return str_repeat('⭐', $this->rider_rating);
    }

    public function getHasRiderRatingAttribute()
    {
        return !is_null($this->rider_rating);
    }

    public function getHasAdminResponseAttribute()
    {
        return !is_null($this->admin_response);
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsPositiveAttribute()
    {
        return $this->rating >= 4;
    }

    public function getIsNegativeAttribute()
    {
        return $this->rating <= 2;
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function approve()
    {
        $this->update(['is_approved' => true]);
    }

    public function disapprove()
    {
        $this->update(['is_approved' => false]);
    }

    public function toggleFeatured()
    {
        $this->update(['is_featured' => !$this->is_featured]);
    }

    public function addAdminResponse($response)
    {
        $this->update([
            'admin_response' => $response,
            'admin_responded_at' => now(),
        ]);
    }

    protected static function booted()
    {
        static::created(function ($review) {
            // Update restaurant rating
            $review->restaurant->updateRating();
        });

        static::updated(function ($review) {
            // Update restaurant rating when review is modified
            if ($review->isDirty('rating')) {
                $review->restaurant->updateRating();
            }
        });

        static::deleted(function ($review) {
            // Update restaurant rating when review is deleted
            $review->restaurant->updateRating();
        });
    }
}