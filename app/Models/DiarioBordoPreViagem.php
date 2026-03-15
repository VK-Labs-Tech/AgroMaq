<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiarioBordoPreViagem extends Model
{
    use HasFactory;

    protected $table = 'diario_bordo_pre_viagens';

    protected $fillable = [
        'diario_bordo_id',
        'carga_descricao',
        'peso_carga_kg',
        'previsao_saida_em',
        'combustivel_percentual',
        'observacoes',
        'payload',
    ];

    protected $casts = [
        'previsao_saida_em' => 'datetime',
        'payload' => 'array',
    ];

    public function diarioBordo(): BelongsTo
    {
        return $this->belongsTo(DiarioBordo::class);
    }
}

