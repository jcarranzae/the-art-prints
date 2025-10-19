<?php

// app/Models/Coupon.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_purchase',
        'usage_limit',
        'usage_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('order_id', 'used_at')->withTimestamps();
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->isBefore($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->isAfter($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedBy(User $user): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->usage_per_user) {
            $usageCount = $this->users()->where('user_id', $user->id)->count();
            
            if ($usageCount >= $this->usage_per_user) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->min_purchase && $subtotal < $this->min_purchase) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($subtotal * $this->value) / 100;
        }

        return min($this->value, $subtotal);
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}