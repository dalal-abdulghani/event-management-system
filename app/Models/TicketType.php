<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity_total',
        'quantity_available',
        'sale_start',
        'sale_end',
        'min_purchase',
        'max_purchase',
        'is_active',
        'benefits',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_start' => 'datetime',
            'sale_end' => 'datetime',
            'is_active' => 'boolean',
            'benefits' => 'array',
        ];
    }

    /**
     * Get the event for this ticket type.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the order items for this ticket type.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the tickets for this ticket type.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Check if this ticket type is available for purchase.
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->quantity_available <= 0) {
            return false;
        }

        $now = now();
        
        if ($this->sale_start && $now->isBefore($this->sale_start)) {
            return false;
        }

        if ($this->sale_end && $now->isAfter($this->sale_end)) {
            return false;
        }

        return true;
    }
}
