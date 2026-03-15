<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiarioBordoAssinatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'diario_bordo_id',
        'signed_by_user_id',
        'assinante_nome',
        'assinatura_base64',
        'documento_hash',
        'ip_address',
        'user_agent',
        'assinado_em',
    ];

    protected $casts = [
        'assinado_em' => 'datetime',
    ];

    public function diarioBordo(): BelongsTo
    {
        return $this->belongsTo(DiarioBordo::class);
    }

    public function signedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by_user_id');
    }
}

