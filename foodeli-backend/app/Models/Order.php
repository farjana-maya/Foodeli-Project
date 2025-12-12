<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\DeliveryRider;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'restaurant_id',
        'rider_id',
        'address_id',
        'items',
        'subtotal',
        'delivery_fee',
        'discount_amount',
        'tax_amount',
        'tip',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_transaction_id',
        'transaction_id',
        'delivery_address',
        'customer_phone',
        'special_instructions',
        'cancellation_reason',
        'scheduled_at',
        'estimated_delivery_time',
        'confirmed_at',
        'preparing_at',
        'prepared_at',
        'ready_at',
        'picked_up_at',
        'delivered_at',
        'cancelled_at'
    ];

    protected $casts = [
        'items' => 'array',
        'delivery_address' => 'array',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tip' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'scheduled_at' => 'datetime',
        'estimated_delivery_time' => 'datetime',
        'confirmed_at' => 'datetime',
        'preparing_at' => 'datetime',
        'prepared_at' => 'datetime',
        'ready_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function rider()
    {
        return $this->belongsTo(DeliveryRider::class, 'rider_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . time() . '-' . rand(1000, 9999);
            }
        });
    }
}