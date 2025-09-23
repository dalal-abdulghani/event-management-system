<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'order_id',
        'order_item_id',
        'ticket_type_id',
        'event_id',
        'user_id',
        'attendee_name',
        'attendee_email',
        'qr_code',
        'barcode',
        'status',
        'checked_in_at',
        'checked_in_by',
        'additional_info',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'additional_info' => 'array',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'TKT-' . strtoupper(Str::random(8));
            }
            
            if (empty($ticket->qr_code)) {
                $ticket->qr_code = Str::random(32);
            }
            
            if (empty($ticket->barcode)) {
                $ticket->barcode = mt_rand(100000000000, 999999999999);
            }
        });
    }

    /**
     * Get the order for this ticket.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order item for this ticket.
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Get the ticket type for this ticket.
     */
    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    /**
     * Get the event for this ticket.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user for this ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the staff member who checked in this ticket.
     */
    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    /**
     * Check if this ticket is valid for check-in.
     */
    public function isValidForCheckIn(): bool
    {
        return $this->status === 'valid';
    }

    /**
     * Check in this ticket.
     */
    public function checkIn(User $staff = null): bool
    {
        if (!$this->isValidForCheckIn()) {
            return false;
        }

        $this->update([
            'status' => 'used',
            'checked_in_at' => now(),
            'checked_in_by' => $staff?->id,
        ]);

        return true;
    }
}
