<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiarioBordoSyncBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'diario_bordo_id',
        'user_id',
        'device_id',
        'payload',
        'sincronizado_em',
    ];

    protected $casts = [
        'payload' => 'array',
        'sincronizado_em' => 'datetime',
    ];

    public function diarioBordo(): BelongsTo
    {
        return $this->belongsTo(DiarioBordo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

