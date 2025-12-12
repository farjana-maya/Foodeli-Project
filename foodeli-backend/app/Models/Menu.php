<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'image',
        'is_available',
        'is_featured',
        'is_vegetarian',
        'is_vegan',
        'preparation_time',
        'calories',
        'ingredients',
        'allergens',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'is_vegetarian' => 'boolean',
        'is_vegan' => 'boolean',
        'preparation_time' => 'integer',
        'calories' => 'integer',
        'ingredients' => 'array',
        'allergens' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($menu) {
            if (empty($menu->slug)) {
                $menu->slug = Str::slug($menu->name);
                
                // Ensure unique slug within restaurant
                $count = static::where('restaurant_id', $menu->restaurant_id)
                    ->whereRaw("slug RLIKE '^{$menu->slug}(-[0-9]+)?$'")
                    ->count();
                    
                if ($count > 0) {
                    $menu->slug = "{$menu->slug}-{$count}";
                }
            }
        });
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVegetarian($query)
    {
        return $query->where('is_vegetarian', true);
    }

    public function scopeVegan($query)
    {
        return $query->where('is_vegan', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // ============================================
    // ACCESSORS & MUTATORS
    // ============================================

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/uploads/menus/' . $this->image);
        }
        return asset('images/default-food.jpg');
    }

    public function getFormattedPriceAttribute()
    {
        return '৳' . number_format($this->price, 2);
    }

    public function getFormattedDiscountPriceAttribute()
    {
        if ($this->discount_price) {
            return '৳' . number_format($this->discount_price, 2);
        }
        return null;
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public function getFormattedFinalPriceAttribute()
    {
        return '৳' . number_format($this->final_price, 2);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_price && $this->price > 0) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getHasDiscountAttribute()
    {
        return !is_null($this->discount_price) && $this->discount_price < $this->price;
    }

    public function getFormattedPreparationTimeAttribute()
    {
        if ($this->preparation_time) {
            return $this->preparation_time . ' mins';
        }
        return null;
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function isAvailable(): bool
    {
        return $this->is_available && $this->restaurant->is_active;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}