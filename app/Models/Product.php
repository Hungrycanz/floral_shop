<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'category_id', 'name', 'description', 'price',
        'stock_quantity', 'image_url', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function occasions()
    {
        return $this->belongsToMany(Occasion::class, 'product_occasions');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating(): ?float
    {
        return $this->reviews()->avg('rating');
    }

    public function reviewCount(): int
    {
        return $this->reviews()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
