<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'long_description',
        'price',
        'compare_price',
        'cost',
        'type',
        'file_path',
        'download_limit',
        'stock',
        'track_inventory',
        'weight',
        'sku',
        'category_id',
        'is_active',
        'is_featured',
        'views',
        'sales_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'track_inventory' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isDigital(): bool
    {
        return $this->type === 'digital';
    }

    public function isPhysical(): bool
    {
        return $this->type === 'physical';
    }

    public function hasDiscount(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function discountPercentage(): ?int
    {
        if (!$this->hasDiscount()) {
            return null;
        }
        
        return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function inStock(): bool
    {
        if (!$this->track_inventory) {
            return true;
        }
        
        return $this->stock > 0;
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function incrementSales(int $quantity = 1): void
    {
        $this->increment('sales_count', $quantity);
    }
}

