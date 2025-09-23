<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'event_id',
        'commission_rate',
        'commission_earned',
        'clicks',
        'conversions',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'commission_earned' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($referral) {
            if (empty($referral->code)) {
                $referral->code = 'REF-' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Get the user (referrer) for this referral.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event for this referral.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Increment the click count.
     */
    public function incrementClicks(): void
    {
        $this->increment('clicks');
    }

    /**
     * Increment the conversion count and add commission.
     */
    public function recordConversion(float $orderAmount): void
    {
        $commission = $orderAmount * ($this->commission_rate / 100);
        
        $this->increment('conversions');
        $this->increment('commission_earned', $commission);
    }
}
