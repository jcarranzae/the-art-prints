<?php

// app/Models/CartItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'product_variant_id',
        'quantity',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPrice(): float
    {
        if ($this->variant && $this->variant->price) {
            return $this->variant->price;
        }

        return $this->product->price;
    }

    public function getTotal(): float
    {
        return $this->getPrice() * $this->quantity;
    }
}