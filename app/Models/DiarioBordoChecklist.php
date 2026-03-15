<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiarioBordoChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'diario_bordo_id',
        'item',
        'marcado',
        'observacao',
        'checado_em',
    ];

    protected $casts = [
        'marcado' => 'boolean',
        'checado_em' => 'datetime',
    ];

    public function diarioBordo(): BelongsTo
    {
        return $this->belongsTo(DiarioBordo::class);
    }
}

