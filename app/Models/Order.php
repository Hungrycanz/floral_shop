<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'courier_id', 'status', 'delivery_zone_id',
        'recipient_name', 'recipient_phone', 'delivery_address',
        'delivery_date', 'card_message', 'total_amount', 'delivered_at',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2',
        'order_date' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function deliveryZone()
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function trackingEvents()
    {
        return $this->hasMany(OrderTrackingEvent::class)->orderByDesc('created_at');
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, OrderItem::class, 'order_id', 'product_id');
    }
}
