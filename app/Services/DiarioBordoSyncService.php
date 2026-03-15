<?php

namespace App\Services;

use App\Models\DiarioBordo;
use App\Repositories\Contracts\DiarioBordoRepositoryInterface;
use Illuminate\Support\Arr;

class DiarioBordoSyncService
{
    public function __construct(
        private readonly DiarioBordoRepositoryInterface $repository,
        private readonly DiarioBordoWorkflowService $workflowService,
    ) {
    }

    public function sync(array $payload, ?int $userId = null): DiarioBordo
    {
        $diario = null;

        if (!empty($payload['diario_uuid'])) {
            $diario = $this->repository->findByUuid($payload['diario_uuid']);
        }

        if ($diario === null) {
            $diario = $this->workflowService->iniciar([
                'motorista_nome' => Arr::get($payload, 'motorista_nome', 'Motorista Offline'),
                'veiculo_identificacao' => Arr::get($payload, 'veiculo_identificacao', 'A definir'),
                'origem' => Arr::get($payload, 'origem', 'Origem offline'),
                'destino' => Arr::get($payload, 'destino', 'Destino offline'),
            ], $userId);
        }

        foreach ($payload['actions'] ?? [] as $action) {
            $type = Arr::get($action, 'type');
            $data = Arr::get($action, 'payload', []);

            if ($type === 'pre_viagem') {
                $this->workflowService->salvarPreViagem($diario, $data);
                continue;
            }

            if ($type === 'checklist') {
                $this->workflowService->salvarChecklist($diario, Arr::get($data, 'items', []));
                continue;
            }

            if ($type === 'transito') {
                $this->workflowService->registrarTransito($diario, $data);
            }
        }

        $this->repository->storeSyncBatch([
            'diario_bordo_id' => $diario->id,
            'user_id' => $userId,
            'device_id' => Arr::get($payload, 'device_id', 'unknown-device'),
            'payload' => $payload,
            'sincronizado_em' => now(),
        ]);

        return $diario->fresh(['preViagem', 'checklists', 'transitos', 'assinatura']);
    }
}

