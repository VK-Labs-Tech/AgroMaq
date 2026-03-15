@extends('layouts.admin-2026')

@php
    $steps = [
        'pre_viagem' => '1. Pre-viagem',
        'checklist' => '2. Checklist',
        'em_transito' => '3. Em transito',
        'encerrado' => '4. Encerrado',
    ];

    $checklistDefaults = [
        'Freios',
        'Pneus',
        'Iluminacao',
        'Extintor',
        'Documentacao',
        'EPI completo',
    ];

    $existingChecklist = $diario->checklists->keyBy('item');
@endphp

@section('title', 'Diario #'.$diario->id)

@section('after_styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        #map {
            height: 360px;
            border-radius: 0.75rem;
        }

        #signature-canvas {
            width: 100%;
            height: 200px;
            border: 1px dashed #adb5bd;
            border-radius: 0.75rem;
            background: #fff;
        }
    </style>
@endsection

@section('content')
    <div
        id="diario-bordo-show"
        data-diario-id="{{ $diario->id }}"
        data-diario-uuid="{{ $diario->uuid }}"
        data-csrf="{{ csrf_token() }}"
        data-transito-url="{{ route('admin.diario-bordo.transito', $diario->id) }}"
        data-feed-url="{{ route('admin.diario-bordo.gps-feed', $diario->id) }}"
        data-sync-url="{{ route('admin.diario-bordo.sync') }}"
    >
        <x-ui.page-header
            :title="'Diario #'.$diario->id.' - '.$diario->motorista_nome"
            :subtitle="$diario->origem.' -> '.$diario->destino.' | UUID: '.$diario->uuid"
        >
            <x-slot:actions>
                <a href="{{ route('admin.diario-bordo.index') }}" class="btn btn-outline-secondary">Voltar</a>
            </x-slot:actions>
        </x-ui.page-header>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex flex-wrap gap-2 mb-4">
            @foreach ($steps as $key => $label)
                <span class="app-chip {{ $diario->status === $key ? 'active' : '' }}">{{ $label }}</span>
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <x-ui.panel title="1) Pre-viagem" class="mb-4">
                    <form method="POST" action="{{ route('admin.diario-bordo.pre-viagem', $diario->id) }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Descricao da carga</label>
                            <textarea class="form-control" name="carga_descricao" rows="2">{{ old('carga_descricao', $diario->preViagem?->carga_descricao) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Peso (kg)</label>
                            <input type="number" step="0.01" class="form-control" name="peso_carga_kg" value="{{ old('peso_carga_kg', $diario->preViagem?->peso_carga_kg) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Previsao saida</label>
                            <input type="datetime-local" class="form-control" name="previsao_saida_em" value="{{ old('previsao_saida_em', optional($diario->preViagem?->previsao_saida_em)->format('Y-m-d\\TH:i')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Combustivel (%)</label>
                            <input type="number" min="0" max="100" class="form-control" name="combustivel_percentual" value="{{ old('combustivel_percentual', $diario->preViagem?->combustivel_percentual) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observacoes</label>
                            <textarea class="form-control" name="observacoes" rows="2">{{ old('observacoes', $diario->preViagem?->observacoes) }}</textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary">Salvar pre-viagem</button>
                        </div>
                    </form>
                </x-ui.panel>

                <x-ui.panel title="2) Checklist" class="mb-4">
                    <form method="POST" action="{{ route('admin.diario-bordo.checklist', $diario->id) }}" class="row g-3">
                        @csrf
                        @foreach ($checklistDefaults as $index => $itemName)
                            @php $item = $existingChecklist->get($itemName); @endphp
                            <div class="col-12 border rounded-3 p-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" name="items[{{ $index }}][marcado]" {{ old("items.$index.marcado", $item?->marcado) ? 'checked' : '' }}>
                                    <input type="hidden" name="items[{{ $index }}][item]" value="{{ $itemName }}">
                                    <label class="form-check-label"><strong>{{ $itemName }}</strong></label>
                                </div>
                                <input class="form-control" name="items[{{ $index }}][observacao]" placeholder="Observacao" value="{{ old("items.$index.observacao", $item?->observacao) }}">
                            </div>
                        @endforeach
                        <div class="col-12 text-end">
                            <button class="btn btn-primary">Salvar checklist</button>
                        </div>
                    </form>
                </x-ui.panel>

                <x-ui.panel title="4) Encerramento e assinatura digital">
                    <form method="POST" action="{{ route('admin.diario-bordo.encerramento', $diario->id) }}" id="encerramento-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nome do assinante</label>
                            <input type="text" name="assinante_nome" class="form-control" value="{{ old('assinante_nome', auth()->user()?->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assinatura</label>
                            <canvas id="signature-canvas"></canvas>
                            <input type="hidden" name="assinatura_base64" id="assinatura-base64">
                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-signature">Limpar assinatura</button>
                            </div>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-success" id="btn-encerrar">Encerrar diario</button>
                        </div>
                    </form>
                </x-ui.panel>
            </div>

            <div class="col-lg-5">
                <x-ui.panel title="3) GPS em tempo real" class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="small app-muted">Coleta em tempo real com fallback offline</span>
                        <span class="badge text-bg-info" id="gps-status">Aguardando</span>
                    </div>

                    <div id="map"></div>

                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-outline-primary" id="start-gps">Iniciar captura GPS</button>
                        <button class="btn btn-outline-secondary" id="sync-offline">Sincronizar offline</button>
                    </div>
                    <small class="app-muted d-block mt-2">Se estiver offline, os pontos sao salvos localmente e enviados ao reconectar.</small>
                </x-ui.panel>

                <x-ui.panel title="Resumo rapido">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Status:</strong> {{ strtoupper(str_replace('_', ' ', $diario->status)) }}</li>
                        <li class="mb-2"><strong>Pontos GPS:</strong> {{ $diario->transitos()->count() }}</li>
                        <li class="mb-2"><strong>Integracao:</strong> {{ strtoupper($diario->integration_status) }}</li>
                        <li><strong>Atualizado:</strong> {{ $diario->updated_at?->format('d/m/Y H:i') }}</li>
                    </ul>
                </x-ui.panel>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    @include('admin.diario_bordo.partials.show-scripts')
@endsection
