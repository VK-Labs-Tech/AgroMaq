@extends('layouts.admin')

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

@section('after_styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        .step-chip {
            border-radius: 999px;
            padding: 0.45rem 0.85rem;
            border: 1px solid #dee2e6;
            font-size: 0.85rem;
            background: #fff;
        }

        .step-chip.active {
            background: #1f6feb;
            border-color: #1f6feb;
            color: #fff;
        }

        #map {
            height: 360px;
            border-radius: 0.5rem;
        }

        #signature-canvas {
            width: 100%;
            height: 200px;
            border: 1px dashed #adb5bd;
            border-radius: 0.5rem;
            background: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0">Diario #{{ $diario->id }} - {{ $diario->motorista_nome }}</h3>
                <small class="text-muted">{{ $diario->origem }} -> {{ $diario->destino }} | UUID: {{ $diario->uuid }}</small>
            </div>
            <a href="{{ route('admin.diario-bordo.index') }}" class="btn btn-outline-secondary">Voltar</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex flex-wrap gap-2 mb-4">
            @foreach ($steps as $key => $label)
                <span class="step-chip {{ $diario->status === $key ? 'active' : '' }}">{{ $label }}</span>
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><strong>1) Pre-viagem</strong></div>
                    <div class="card-body">
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
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><strong>2) Checklist</strong></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.diario-bordo.checklist', $diario->id) }}" class="row g-3">
                            @csrf
                            @foreach ($checklistDefaults as $index => $itemName)
                                @php $item = $existingChecklist->get($itemName); @endphp
                                <div class="col-12 border rounded p-3">
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
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white"><strong>4) Encerramento e assinatura digital</strong></div>
                    <div class="card-body">
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
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <strong>3) GPS em tempo real</strong>
                        <span class="badge bg-info" id="gps-status">Aguardando</span>
                    </div>
                    <div class="card-body">
                        <div id="map"></div>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-outline-primary" id="start-gps">Iniciar captura GPS</button>
                            <button class="btn btn-outline-secondary" id="sync-offline">Sincronizar offline</button>
                        </div>
                        <small class="text-muted d-block mt-2">Se estiver offline, os pontos sao salvos localmente e enviados ao reconectar.</small>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white"><strong>Resumo rapido</strong></div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><strong>Status:</strong> {{ strtoupper(str_replace('_', ' ', $diario->status)) }}</li>
                            <li class="mb-2"><strong>Pontos GPS:</strong> {{ $diario->transitos()->count() }}</li>
                            <li class="mb-2"><strong>Integracao:</strong> {{ strtoupper($diario->integration_status) }}</li>
                            <li><strong>Atualizado:</strong> {{ $diario->updated_at?->format('d/m/Y H:i') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        (() => {
            const diarioId = {{ $diario->id }};
            const csrfToken = @json(csrf_token());
            const transitoUrl = @json(route('admin.diario-bordo.transito', $diario->id));
            const feedUrl = @json(route('admin.diario-bordo.gps-feed', $diario->id));
            const syncUrl = @json(route('admin.diario-bordo.sync'));
            const storageKey = `diario_sync_queue_${diarioId}`;

            const map = L.map('map').setView([-15.78, -47.93], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const routeLine = L.polyline([], { color: '#1f6feb' }).addTo(map);
            let marker = null;

            const setStatus = (text) => {
                document.getElementById('gps-status').textContent = text;
            };

            const pushOffline = (entry) => {
                const current = JSON.parse(localStorage.getItem(storageKey) || '[]');
                current.push(entry);
                localStorage.setItem(storageKey, JSON.stringify(current));
            };

            const postTransito = async (payload) => {
                try {
                    const response = await fetch(transitoUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) {
                        throw new Error('Falha ao enviar ponto de GPS');
                    }
                } catch (error) {
                    pushOffline({ type: 'transito', payload });
                }
            };

            const syncOffline = async () => {
                const actions = JSON.parse(localStorage.getItem(storageKey) || '[]');

                if (!actions.length) {
                    setStatus('Offline queue vazia');
                    return;
                }

                const response = await fetch(syncUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        diario_uuid: '{{ $diario->uuid }}',
                        device_id: navigator.userAgent.slice(0, 120),
                        actions
                    })
                });

                if (response.ok) {
                    localStorage.removeItem(storageKey);
                    setStatus('Sincronizado');
                }
            };

            document.getElementById('sync-offline').addEventListener('click', syncOffline);

            document.getElementById('start-gps').addEventListener('click', () => {
                if (!navigator.geolocation) {
                    setStatus('GPS indisponivel');
                    return;
                }

                setStatus('Capturando...');

                navigator.geolocation.watchPosition((position) => {
                    const point = [position.coords.latitude, position.coords.longitude];
                    routeLine.addLatLng(point);

                    if (!marker) {
                        marker = L.marker(point).addTo(map);
                    } else {
                        marker.setLatLng(point);
                    }

                    map.setView(point, 16);

                    const payload = {
                        offline_id: `${Date.now()}-${Math.random().toString(16).slice(2)}`,
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        velocidade_kmh: position.coords.speed ? (position.coords.speed * 3.6) : null,
                        precisao_m: position.coords.accuracy,
                        registrado_em: new Date().toISOString(),
                    };

                    postTransito(payload);
                }, () => {
                    setStatus('Erro ao capturar GPS');
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 1000,
                    timeout: 7000,
                });
            });

            const refreshFeed = async () => {
                const response = await fetch(feedUrl, { headers: { 'Accept': 'application/json' } });
                if (!response.ok) {
                    return;
                }

                const data = await response.json();
                setStatus(data.status);

                const points = (data.points || []).map((item) => [parseFloat(item.latitude), parseFloat(item.longitude)]);
                routeLine.setLatLngs(points);

                if (points.length > 0) {
                    const last = points[points.length - 1];
                    if (!marker) {
                        marker = L.marker(last).addTo(map);
                    } else {
                        marker.setLatLng(last);
                    }
                    map.setView(last, 14);
                }
            };

            setInterval(refreshFeed, 10000);
            refreshFeed();

            const canvas = document.getElementById('signature-canvas');
            const signatureInput = document.getElementById('assinatura-base64');
            const ctx = canvas.getContext('2d');
            let drawing = false;

            const resizeCanvas = () => {
                const ratio = window.devicePixelRatio || 1;
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                ctx.scale(ratio, ratio);
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
            };

            resizeCanvas();

            const getPos = (event) => {
                const rect = canvas.getBoundingClientRect();
                return {
                    x: event.clientX - rect.left,
                    y: event.clientY - rect.top,
                };
            };

            canvas.addEventListener('pointerdown', (event) => {
                drawing = true;
                const pos = getPos(event);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            });

            canvas.addEventListener('pointermove', (event) => {
                if (!drawing) {
                    return;
                }

                const pos = getPos(event);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            });

            const endDrawing = () => {
                drawing = false;
                signatureInput.value = canvas.toDataURL('image/png');
            };

            canvas.addEventListener('pointerup', endDrawing);
            canvas.addEventListener('pointerleave', endDrawing);

            document.getElementById('clear-signature').addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                signatureInput.value = '';
            });

            document.getElementById('encerramento-form').addEventListener('submit', (event) => {
                if (!signatureInput.value) {
                    event.preventDefault();
                    alert('Assine no campo de assinatura antes de encerrar.');
                }
            });
        })();
    </script>
@endsection


