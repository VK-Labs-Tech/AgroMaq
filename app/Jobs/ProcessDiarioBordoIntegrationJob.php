<?php

namespace App\Jobs;

use App\Models\DiarioBordoIntegration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class ProcessDiarioBordoIntegrationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly int $integrationId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $integration = DiarioBordoIntegration::query()->find($this->integrationId);

        if ($integration === null) {
            return;
        }

        $url = config('services.diario_bordo.webhook_url');

        if (empty($url)) {
            $integration->update([
                'status' => 'skipped',
                'resposta' => 'Webhook URL nao configurada.',
                'processado_em' => now(),
            ]);

            return;
        }

        $response = Http::timeout(10)->post($url, $integration->payload);

        $integration->update([
            'status' => $response->successful() ? 'success' : 'error',
            'tentativas' => $integration->tentativas + 1,
            'resposta' => $response->body(),
            'processado_em' => now(),
        ]);

        if ($response->successful() && $integration->diarioBordo !== null) {
            $integration->diarioBordo->forceFill(['integration_status' => 'sent'])->save();
        }
    }
}

