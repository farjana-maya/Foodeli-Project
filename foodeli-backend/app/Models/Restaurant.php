<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\MenuItem;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'cuisine_type',
        'address',
        'city',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'email',
        'logo',
        'cover_image',
        'rating',
        'total_reviews',
        'delivery_time',
        'delivery_fee',
        'min_order_amount',
        'is_active',
        'is_featured',
        'is_verified',
        'opening_time',
        'closing_time',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'total_reviews' => 'integer',
        'delivery_time' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($restaurant) {
            if (empty($restaurant->slug)) {
                $restaurant->slug = Str::slug($restaurant->name);
                
                // Ensure unique slug
                $count = static::whereRaw("slug RLIKE '^{$restaurant->slug}(-[0-9]+)?$'")->count();
                if ($count > 0) {
                    $restaurant->slug = "{$restaurant->slug}-{$count}";
                }
            }
        });
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('cuisine_type', 'like', "%{$search}%");
        });
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByCuisine($query, $cuisine)
    {
        return $query->where('cuisine_type', $cuisine);
    }

    public function scopeMinRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    public function scopeWithinDeliveryFee($query, $maxFee)
    {
        return $query->where('delivery_fee', '<=', $maxFee);
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 10)
    {
        // Using Haversine formula
        $query->selectRaw("*, 
            (6371 * acos(cos(radians(?)) 
            * cos(radians(latitude)) 
            * cos(radians(longitude) - radians(?)) 
            + sin(radians(?)) 
            * sin(radians(latitude)))) AS distance", 
            [$latitude, $longitude, $latitude]
        )
        ->having('distance', '<=', $radius)
        ->orderBy('distance');

        return $query;
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/uploads/restaurants/logos/' . $this->logo);
        }
        return asset('images/default-restaurant-logo.png');
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/uploads/restaurants/covers/' . $this->cover_image);
        }
        return asset('images/default-restaurant-cover.jpg');
    }

    public function getFormattedDeliveryFeeAttribute()
    {
        if ($this->delivery_fee == 0) {
            return 'Free Delivery';
        }
        return '৳' . number_format($this->delivery_fee, 2);
    }

    public function getFormattedMinOrderAttribute()
    {
        return '৳' . number_format($this->min_order_amount, 2);
    }

    public function getFormattedDeliveryTimeAttribute()
    {
        return $this->delivery_time . ' mins';
    }

    public function getIsOpenAttribute()
    {
        if (!$this->opening_time || !$this->closing_time) {
            return true; // 24/7 if no times set
        }

        $now = now()->format('H:i');
        $opening = $this->opening_time->format('H:i');
        $closing = $this->closing_time->format('H:i');

        if ($closing < $opening) {
            // Crosses midnight
            return $now >= $opening || $now <= $closing;
        }

        return $now >= $opening && $now <= $closing;
    }

    public function getRatingStarsAttribute()
    {
        return round($this->rating * 2) / 2; // Round to nearest 0.5
    }

    public function getHasCoordinatesAttribute()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function updateRating()
    {
        $avgRating = $this->reviews()->avg('rating');
        $totalReviews = $this->reviews()->count();

        $this->update([
            'rating' => $avgRating ?? 0,
            'total_reviews' => $totalReviews,
        ]);
    }

    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    public function calculateDistance($latitude, $longitude)
    {
        if (!$this->has_coordinates) {
            return null;
        }

        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius, 2);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}