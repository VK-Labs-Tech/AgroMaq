<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiarioBordoIntegration extends Model
{

    protected $fillable = [
        'diario_bordo_id',
        'canal',
        'status',
        'tentativas',
        'payload',
        'resposta',
        'processado_em',
    ];

    protected $casts = [
        'payload' => 'array',
        'processado_em' => 'datetime',
    ];

    public function diarioBordo(): BelongsTo
    {
        return $this->belongsTo(DiarioBordo::class);
    }
}

