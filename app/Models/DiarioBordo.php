<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class DiarioBordo extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'status',
        'motorista_nome',
        'veiculo_identificacao',
        'origem',
        'destino',
        'iniciado_em',
        'encerrado_em',
        'integration_status',
        'resumo_hash',
    ];

    protected $casts = [
        'iniciado_em' => 'datetime',
        'encerrado_em' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $diarioBordo): void {
            if (empty($diarioBordo->uuid)) {
                $diarioBordo->uuid = (string) Str::uuid();
            }

            if (empty($diarioBordo->iniciado_em)) {
                $diarioBordo->iniciado_em = now();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preViagem(): HasOne
    {
        return $this->hasOne(DiarioBordoPreViagem::class);
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(DiarioBordoChecklist::class);
    }

    public function transitos(): HasMany
    {
        return $this->hasMany(DiarioBordoTransito::class)->orderByDesc('registrado_em');
    }

    public function assinatura(): HasOne
    {
        return $this->hasOne(DiarioBordoAssinatura::class);
    }

    public function syncBatches(): HasMany
    {
        return $this->hasMany(DiarioBordoSyncBatch::class);
    }

    public function integrations(): HasMany
    {
        return $this->hasMany(DiarioBordoIntegration::class);
    }
}

