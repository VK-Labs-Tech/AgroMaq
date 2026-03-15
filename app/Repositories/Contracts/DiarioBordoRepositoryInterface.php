<?php

namespace App\Repositories\Contracts;

use App\Models\DiarioBordo;
use App\Models\DiarioBordoAssinatura;
use App\Models\DiarioBordoPreViagem;
use App\Models\DiarioBordoSyncBatch;
use App\Models\DiarioBordoTransito;
use Illuminate\Support\Collection;

interface DiarioBordoRepositoryInterface
{
    public function createDraft(array $data): DiarioBordo;

    public function findById(int $id): ?DiarioBordo;

    public function findByUuid(string $uuid): ?DiarioBordo;

    public function dashboardList(?int $userId = null): Collection;

    public function savePreViagem(DiarioBordo $diarioBordo, array $data): DiarioBordoPreViagem;

    public function replaceChecklist(DiarioBordo $diarioBordo, array $items): void;

    public function addTransito(DiarioBordo $diarioBordo, array $data): DiarioBordoTransito;

    public function assinarEncerramento(DiarioBordo $diarioBordo, array $data): DiarioBordoAssinatura;

    public function updateStatus(DiarioBordo $diarioBordo, string $status): DiarioBordo;

    public function storeSyncBatch(array $data): DiarioBordoSyncBatch;
}

