<?php

namespace App\Services;

use App\Jobs\ProcessDiarioBordoIntegrationJob;
use App\Models\DiarioBordo;
use App\Models\DiarioBordoIntegration;

class DiarioBordoIntegrationService
{
    public function queueWebhook(DiarioBordo $diarioBordo, string $evento): void
    {
        $integration = DiarioBordoIntegration::query()->create([
            'diario_bordo_id' => $diarioBordo->id,
            'status' => 'pending',
            'payload' => [
                'evento' => $evento,
                'diario_uuid' => $diarioBordo->uuid,
                'status' => $diarioBordo->status,
                'encerrado_em' => $diarioBordo->encerrado_em?->toIso8601String(),
            ],
        ]);

        ProcessDiarioBordoIntegrationJob::dispatch($integration->id);
    }
}

