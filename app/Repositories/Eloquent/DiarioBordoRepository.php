<?php

namespace App\Repositories\Eloquent;

use App\Models\DiarioBordo;
use App\Models\DiarioBordoAssinatura;
use App\Models\DiarioBordoChecklist;
use App\Models\DiarioBordoPreViagem;
use App\Models\DiarioBordoSyncBatch;
use App\Models\DiarioBordoTransito;
use App\Repositories\Contracts\DiarioBordoRepositoryInterface;
use Illuminate\Support\Collection;

class DiarioBordoRepository implements DiarioBordoRepositoryInterface
{
    public function createDraft(array $data): DiarioBordo
    {
        return DiarioBordo::query()->create($data);
    }

    public function findById(int $id): ?DiarioBordo
    {
        return DiarioBordo::query()->with(['preViagem', 'checklists', 'transitos', 'assinatura'])->find($id);
    }

    public function findByUuid(string $uuid): ?DiarioBordo
    {
        return DiarioBordo::query()->with(['preViagem', 'checklists', 'transitos', 'assinatura'])->where('uuid', $uuid)->first();
    }

    public function dashboardList(?int $userId = null): Collection
    {
        $query = DiarioBordo::query()->withCount('transitos')->latest();

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        return $query->take(20)->get();
    }

    public function savePreViagem(DiarioBordo $diarioBordo, array $data): DiarioBordoPreViagem
    {
        return DiarioBordoPreViagem::query()->updateOrCreate(
            ['diario_bordo_id' => $diarioBordo->id],
            $data
        );
    }

    public function replaceChecklist(DiarioBordo $diarioBordo, array $items): void
    {
        DiarioBordoChecklist::query()->where('diario_bordo_id', $diarioBordo->id)->delete();

        foreach ($items as $item) {
            DiarioBordoChecklist::query()->create([
                'diario_bordo_id' => $diarioBordo->id,
                'item' => $item['item'],
                'marcado' => (bool) ($item['marcado'] ?? false),
                'observacao' => $item['observacao'] ?? null,
                'checado_em' => $item['checado_em'] ?? now(),
            ]);
        }
    }

    public function addTransito(DiarioBordo $diarioBordo, array $data): DiarioBordoTransito
    {
        if (!empty($data['offline_id'])) {
            $existing = DiarioBordoTransito::query()
                ->where('diario_bordo_id', $diarioBordo->id)
                ->where('offline_id', $data['offline_id'])
                ->first();

            if ($existing !== null) {
                return $existing;
            }
        }

        return DiarioBordoTransito::query()->create(array_merge($data, [
            'diario_bordo_id' => $diarioBordo->id,
        ]));
    }

    public function assinarEncerramento(DiarioBordo $diarioBordo, array $data): DiarioBordoAssinatura
    {
        return DiarioBordoAssinatura::query()->updateOrCreate(
            ['diario_bordo_id' => $diarioBordo->id],
            $data
        );
    }

    public function updateStatus(DiarioBordo $diarioBordo, string $status): DiarioBordo
    {
        $diarioBordo->update(['status' => $status]);

        return $diarioBordo->fresh();
    }

    public function storeSyncBatch(array $data): DiarioBordoSyncBatch
    {
        return DiarioBordoSyncBatch::query()->create($data);
    }
}

