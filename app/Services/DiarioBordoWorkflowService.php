<?php

namespace App\Services;

use App\Enums\DiarioBordoStatus;
use App\Models\DiarioBordo;
use App\Repositories\Contracts\DiarioBordoRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class DiarioBordoWorkflowService
{
    public function __construct(
        private readonly DiarioBordoRepositoryInterface $repository,
        private readonly DiarioBordoIntegrationService $integrationService,
    ) {
    }

    public function iniciar(array $data, ?int $userId = null): DiarioBordo
    {
        $data['user_id'] = $userId;
        $data['status'] = DiarioBordoStatus::PreViagem->value;

        return $this->repository->createDraft($data);
    }

    public function salvarPreViagem(DiarioBordo $diarioBordo, array $data): DiarioBordo
    {
        DB::transaction(function () use ($diarioBordo, $data): void {
            $this->repository->savePreViagem($diarioBordo, $data);
            $this->repository->updateStatus($diarioBordo, DiarioBordoStatus::Checklist->value);
        });

        $atualizado = $diarioBordo->fresh(['preViagem']);
        $this->integrationService->queueWebhook($atualizado, 'pre_viagem_finalizada');

        return $atualizado;
    }

    public function salvarChecklist(DiarioBordo $diarioBordo, array $items): DiarioBordo
    {
        if (count($items) === 0) {
            throw new InvalidArgumentException('Checklist nao pode ser vazio.');
        }

        DB::transaction(function () use ($diarioBordo, $items): void {
            $this->repository->replaceChecklist($diarioBordo, $items);
            $this->repository->updateStatus($diarioBordo, DiarioBordoStatus::EmTransito->value);
        });

        $atualizado = $diarioBordo->fresh(['checklists']);
        $this->integrationService->queueWebhook($atualizado, 'checklist_finalizado');

        return $atualizado;
    }

    public function registrarTransito(DiarioBordo $diarioBordo, array $data): void
    {
        if ($diarioBordo->status === DiarioBordoStatus::Encerrado->value) {
            throw new InvalidArgumentException('Diario encerrado nao aceita novos pontos de GPS.');
        }

        $this->repository->addTransito($diarioBordo, $data);
    }

    public function encerrarComAssinatura(DiarioBordo $diarioBordo, array $data): DiarioBordo
    {
        if ($diarioBordo->transitos()->count() === 0) {
            throw new InvalidArgumentException('E necessario registrar pontos em transito antes de encerrar.');
        }

        DB::transaction(function () use ($diarioBordo, $data): void {
            $hash = Hash::make($diarioBordo->uuid.'|'.($data['assinatura_base64'] ?? '').'|'.now()->timestamp);

            $this->repository->assinarEncerramento($diarioBordo, array_merge($data, [
                'documento_hash' => $hash,
                'assinado_em' => now(),
            ]));

            $diarioBordo->update([
                'status' => DiarioBordoStatus::Encerrado->value,
                'encerrado_em' => now(),
                'resumo_hash' => $hash,
                'integration_status' => 'queued',
            ]);
        });

        $atualizado = $diarioBordo->fresh(['assinatura']);
        $this->integrationService->queueWebhook($atualizado, 'diario_encerrado');

        return $atualizado;
    }
}

