<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiarioBordo\EncerrarDiarioRequest;
use App\Http\Requests\DiarioBordo\StoreChecklistRequest;
use App\Http\Requests\DiarioBordo\StorePreViagemRequest;
use App\Http\Requests\DiarioBordo\StoreTransitoRequest;
use App\Models\DiarioBordo;
use App\Repositories\Contracts\DiarioBordoRepositoryInterface;
use App\Services\DiarioBordoWorkflowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class DiarioBordoController extends Controller
{
    public function __construct(
        private readonly DiarioBordoRepositoryInterface $repository,
        private readonly DiarioBordoWorkflowService $workflowService,
    ) {
    }

    public function index(): View
    {
        $userId = Auth::id();
        $diarios = $this->repository->dashboardList($userId);

        return view('admin.diario_bordo.index', [
            'diarios' => $diarios,
            'metrics' => [
                'abertos' => $diarios->where('status', '!=', 'encerrado')->count(),
                'encerrados' => $diarios->where('status', 'encerrado')->count(),
                'total' => $diarios->count(),
            ],
        ]);
    }

    public function create(): RedirectResponse
    {
        $diario = $this->workflowService->iniciar([
            'motorista_nome' => Auth::user()?->name ?? 'Motorista',
            'veiculo_identificacao' => 'Frota-001',
            'origem' => 'Origem pendente',
            'destino' => 'Destino pendente',
        ], Auth::id());

        return redirect()->route('admin.diario-bordo.show', $diario->id)
            ->with('success', 'Diario de bordo criado.');
    }

    public function show(int $id): View
    {
        $diario = $this->repository->findById($id);
        abort_if($diario === null, 404);

        return view('admin.diario_bordo.show', [
            'diario' => $diario,
        ]);
    }

    public function salvarPreViagem(StorePreViagemRequest $request, int $id): RedirectResponse
    {
        $diario = $this->repository->findById($id);
        abort_if($diario === null, 404);

        $this->workflowService->salvarPreViagem($diario, $request->validated());

        return redirect()->route('admin.diario-bordo.show', $id)
            ->with('success', 'Pre-viagem salva com sucesso.');
    }

    public function salvarChecklist(StoreChecklistRequest $request, int $id): RedirectResponse
    {
        $diario = $this->repository->findById($id);
        abort_if($diario === null, 404);

        $this->workflowService->salvarChecklist($diario, $request->validated('items'));

        return redirect()->route('admin.diario-bordo.show', $id)
            ->with('success', 'Checklist salvo com sucesso.');
    }

    public function registrarTransito(StoreTransitoRequest $request, int $id): JsonResponse
    {
        $diario = $this->repository->findById($id);
        abort_if($diario === null, 404);

        try {
            $payload = $request->validated();
            $payload['registrado_em'] = $payload['registrado_em'] ?? now();
            $this->workflowService->registrarTransito($diario, $payload);
        } catch (Throwable $exception) {
            return response()->json([
                'ok' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'last_position' => $diario->fresh()->transitos()->first(),
        ]);
    }

    public function encerramento(EncerrarDiarioRequest $request, int $id): RedirectResponse
    {
        $diario = $this->repository->findById($id);
        abort_if($diario === null, 404);

        $this->workflowService->encerrarComAssinatura($diario, array_merge(
            $request->validated(),
            [
                'signed_by_user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]
        ));

        return redirect()->route('admin.diario-bordo.show', $id)
            ->with('success', 'Diario encerrado e assinado digitalmente.');
    }

    public function gpsFeed(int $id): JsonResponse
    {
        $diario = $this->repository->findById($id);
        abort_if($diario === null, 404);

        return response()->json([
            'status' => $diario->status,
            'points' => $diario->transitos()->limit(100)->get()->reverse()->values(),
        ]);
    }
}

