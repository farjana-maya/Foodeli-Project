<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'email',
        'address',
        'city',
        'postal_code',
        'date_of_birth',
        'nid_number',
        'driving_license',
        'vehicle_type',
        'vehicle_model',
        'vehicle_number',
        'profile_photo',
        'nid_photo',
        'license_photo',
        'vehicle_photo',
        'status',
        'is_active',
        'is_available',
        'rating',
        'total_deliveries'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'rating' => 'decimal:2',
        'total_deliveries' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}