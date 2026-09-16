<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTrackingEvent extends Model
{
    public const STATUS_PLACED = 'placed';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_OUT_FOR_PICKUP = 'out_for_pickup';

    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_id', 'status', 'location', 'note',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PLACED => 'Placed',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_OUT_FOR_PICKUP => 'Out for pickup',
            self::STATUS_OUT_FOR_DELIVERY => 'Out for delivery',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
