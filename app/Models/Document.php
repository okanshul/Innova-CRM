<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'file_type',
        'file_size',
        'documentable_type',
        'documentable_id',
        'uploaded_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
