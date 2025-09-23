<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    use HasFactory;

    protected $table = 'event_sessions';

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'speaker_name',
        'speaker_bio',
        'speaker_image',
        'location',
        'capacity',
        'additional_info',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'additional_info' => 'array',
        ];
    }

    /**
     * Get the event for this session.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
