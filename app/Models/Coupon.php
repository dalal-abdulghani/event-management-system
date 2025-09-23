<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
        'applicable_events',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'minimum_amount' => 'decimal:2',
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
            'is_active' => 'boolean',
            'applicable_events' => 'array',
        ];
    }

    /**
     * Get the orders that used this coupon.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if this coupon is valid.
     */
    public function isValid(float $orderAmount = 0, int $eventId = null, int $userId = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($now->isBefore($this->valid_from) || $now->isAfter($this->valid_until)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($this->minimum_amount && $orderAmount < $this->minimum_amount) {
            return false;
        }

        if ($this->applicable_events && $eventId && !in_array($eventId, $this->applicable_events)) {
            return false;
        }

        if ($this->usage_limit_per_user && $userId) {
            $userUsageCount = $this->orders()->where('user_id', $userId)->count();
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate the discount amount.
     */
    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            return $amount * ($this->value / 100);
        }

        return min($this->value, $amount);
    }

    /**
     * Increment the usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}
