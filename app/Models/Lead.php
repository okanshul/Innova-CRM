<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone',
        'source',
        'status',
        'estimated_value',
        'owner_id',
        'converted_contact_id',
        'converted_deal_id',
        'converted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'converted_at' => 'datetime',
    ];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
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

    public function convertedContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'converted_contact_id');
    }

    public function convertedDeal(): BelongsTo
    {
        return $this->belongsTo(Deal::class, 'converted_deal_id');
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function meetings(): MorphMany
    {
        return $this->morphMany(Meeting::class, 'meetable');
    }
}
