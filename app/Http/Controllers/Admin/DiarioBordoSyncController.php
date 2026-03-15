<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiarioBordo\SyncDiarioRequest;
use App\Services\DiarioBordoSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DiarioBordoSyncController extends Controller
{
    public function __construct(private readonly DiarioBordoSyncService $syncService)
    {
    }

    public function sync(SyncDiarioRequest $request): JsonResponse
    {
        $diario = $this->syncService->sync($request->validated(), Auth::id());

        return response()->json([
            'ok' => true,
            'diario_id' => $diario->id,
            'diario_uuid' => $diario->uuid,
            'status' => $diario->status,
        ]);
    }
}

