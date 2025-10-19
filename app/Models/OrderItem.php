<?php

// app/Models/OrderItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'price',
        'quantity',
        'total',
        'download_token',
        'download_count',
        'last_downloaded_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'last_downloaded_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($item) {
            if (empty($item->download_token) && $item->product->isDigital()) {
                $item->download_token = Str::random(64);
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function canDownload(): bool
    {
        if (!$this->product->isDigital()) {
            return false;
        }

        $limit = $this->product->download_limit;
        
        if ($limit === null) {
            return true;
        }

        return $this->download_count < $limit;
    }

    public function incrementDownload(): void
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => now()]);
    }
}