<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_number',
        'vehicle_model',
        'license_number',
        'license_expiry',
        'license_image',
        'id_card_number',
        'id_card_image',
        'is_available',
        'is_verified',
        'is_active',
        'current_latitude',
        'current_longitude',
        'location_updated_at',
        'rating',
        'total_deliveries',
        'completed_deliveries',
        'cancelled_deliveries',
        'bank_name',
        'account_number',
        'account_holder_name',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'is_available' => 'boolean',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'location_updated_at' => 'datetime',
        'rating' => 'decimal:2',
        'total_deliveries' => 'integer',
        'completed_deliveries' => 'integer',
        'cancelled_deliveries' => 'integer',
    ];

    // Constants
    const VEHICLE_BICYCLE = 'bicycle';
    const VEHICLE_MOTORCYCLE = 'motorcycle';
    const VEHICLE_CAR = 'car';
    const VEHICLE_SCOOTER = 'scooter';

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrders()
    {
        return $this->orders()->whereNotIn('status', [
            Order::STATUS_DELIVERED, 
            Order::STATUS_CANCELLED, 
            Order::STATUS_REJECTED
        ]);
    }

    public function completedOrders()
    {
        return $this->orders()->where('status', Order::STATUS_DELIVERED);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                     ->where('is_verified', true)
                     ->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByVehicleType($query, $type)
    {
        return $query->where('vehicle_type', $type);
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 5)
    {
        return $query->selectRaw("*, 
            (6371 * acos(cos(radians(?)) 
            * cos(radians(current_latitude)) 
            * cos(radians(current_longitude) - radians(?)) 
            + sin(radians(?)) 
            * sin(radians(current_latitude)))) AS distance", 
            [$latitude, $longitude, $latitude]
        )
        ->having('distance', '<=', $radius)
        ->orderBy('distance');
    }

    public function scopeMinRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getLicenseImageUrlAttribute()
    {
        if ($this->license_image) {
            return asset('storage/uploads/riders/documents/' . $this->license_image);
        }
        return null;
    }

    public function getIdCardImageUrlAttribute()
    {
        if ($this->id_card_image) {
            return asset('storage/uploads/riders/documents/' . $this->id_card_image);
        }
        return null;
    }

    public function getVehicleTypeFormattedAttribute()
    {
        return ucfirst($this->vehicle_type);
    }

    public function getRatingStarsAttribute()
    {
        return round($this->rating * 2) / 2;
    }

    public function getCompletionRateAttribute()
    {
        if ($this->total_deliveries == 0) {
            return 0;
        }
        return round(($this->completed_deliveries / $this->total_deliveries) * 100, 2);
    }

    public function getHasCurrentLocationAttribute()
    {
        return !is_null($this->current_latitude) && !is_null($this->current_longitude);
    }

    public function getIsLicenseExpiredAttribute()
    {
        if (!$this->license_expiry) {
            return false;
        }
        return $this->license_expiry->isPast();
    }

    public function getLocationLastUpdatedAttribute()
    {
        if (!$this->location_updated_at) {
            return null;
        }
        return $this->location_updated_at->diffForHumans();
    }

    public function getHasActiveOrdersAttribute()
    {
        return $this->activeOrders()->count() > 0;
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function updateLocation($latitude, $longitude)
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'location_updated_at' => now(),
        ]);
    }

    public function toggleAvailability()
    {
        $this->update(['is_available' => !$this->is_available]);
    }

    public function goOnline()
    {
        if ($this->is_verified && $this->is_active) {
            $this->update(['is_available' => true]);
            return true;
        }
        return false;
    }

    public function goOffline()
    {
        $this->update(['is_available' => false]);
    }

    public function verify()
    {
        $this->update(['is_verified' => true]);
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate()
    {
        $this->update([
            'is_active' => false,
            'is_available' => false,
        ]);
    }

    public function incrementDelivery($completed = true)
    {
        $this->increment('total_deliveries');
        if ($completed) {
            $this->increment('completed_deliveries');
        } else {
            $this->increment('cancelled_deliveries');
        }
    }

    public function updateRating()
    {
        $avgRating = $this->orders()
            ->whereHas('review', function($query) {
                $query->whereNotNull('rider_rating');
            })
            ->with('review')
            ->get()
            ->avg('review.rider_rating');

        $this->update(['rating' => $avgRating ?? 0]);
    }

    public function calculateDistance($latitude, $longitude)
    {
        if (!$this->has_current_location) {
            return null;
        }

        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->current_latitude);
        $lonFrom = deg2rad($this->current_longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius, 2);
    }
}