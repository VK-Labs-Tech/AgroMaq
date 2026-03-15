<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiarioBordoTransito extends Model
{
    use HasFactory;

    protected $fillable = [
        'diario_bordo_id',
        'offline_id',
        'latitude',
        'longitude',
        'velocidade_kmh',
        'precisao_m',
        'registrado_em',
    ];

    protected $casts = [
        'registrado_em' => 'datetime',
    ];

    public function diarioBordo(): BelongsTo
    {
        return $this->belongsTo(DiarioBordo::class);
    }
}

