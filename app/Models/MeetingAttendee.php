<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MeetingAttendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'attendee_type',
        'attendee_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function attendee(): MorphTo
    {
        return $this->morphTo();
    }
}
