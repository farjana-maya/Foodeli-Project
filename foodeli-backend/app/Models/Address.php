<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'address_line',
        'building_name',
        'floor_unit',
        'city',
        'area',
        'postal_code',
        'latitude',
        'longitude',
        'delivery_instructions',
        'is_default',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_default' => 'boolean',
    ];

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

    // ============================================
    // SCOPES
    // ============================================

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByLabel($query, $label)
    {
        return $query->where('label', $label);
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address_line,
            $this->building_name,
            $this->floor_unit,
            $this->area,
            $this->city,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }

    public function getFormattedLabelAttribute()
    {
        return ucfirst($this->label);
    }

    public function getHasCoordinatesAttribute()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function setAsDefault()
    {
        // Remove default from other addresses
        $this->user->addresses()->where('id', '!=', $this->id)->update(['is_default' => false]);
        
        // Set this as default
        $this->update(['is_default' => true]);
    }

    public function calculateDistance($latitude, $longitude)
    {
        if (!$this->has_coordinates) {
            return null;
        }

        // Haversine formula to calculate distance
        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}