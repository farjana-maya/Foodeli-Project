<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_available',
        'is_featured',
        'preparation_time',
        'ingredients',
        'allergens',
        'calories',
        'rating',
        'total_reviews',
        'order_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'ingredients' => 'array',
        'allergens' => 'array',
        'preparation_time' => 'integer',
        'calories' => 'integer',
        'total_reviews' => 'integer',
        'order_count' => 'integer'
    ];

    // Relationships
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (strpos($this->image, 'menu_items/') === 0) {
                return asset('storage/' . $this->image);
            }
            return asset('storage/menu_items/' . $this->image);
        }
        return 'data:image/svg+xml;base64,' . base64_encode('<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f3f4f6"/><text x="50%" y="50%" font-family="Arial" font-size="16" fill="#9ca3af" text-anchor="middle" dy=".3em">No Image</text></svg>');
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', 'LIKE', '%' . $category . '%');
    }

    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function scopeByDietaryInfo($query, $dietary)
    {
        return $query->whereJsonContains('dietary_info', $dietary);
    }
}