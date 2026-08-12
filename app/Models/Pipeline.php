<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pipeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_default',
        'order',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'order' => 'integer',
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

    public function stages(): HasMany
    {
        return $this->hasMany(PipelineStage::class)->orderBy('order');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
