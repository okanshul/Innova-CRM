<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'location',
        'meeting_link',
        'start_at',
        'end_at',
        'status',
        'outcome_summary',
        'host_id',
        'meetable_type',
        'meetable_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

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

    public function meetable(): MorphTo
    {
        return $this->morphTo();
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(MeetingAttendee::class);
    }
}
