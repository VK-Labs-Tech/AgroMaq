<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    (() => {
        const root = document.getElementById('diario-bordo-show');
        if (!root) {
            return;
        }

        const diarioId = Number(root.dataset.diarioId);
        const diarioUuid = root.dataset.diarioUuid;
        const csrfToken = root.dataset.csrf;
        const transitoUrl = root.dataset.transitoUrl;
        const feedUrl = root.dataset.feedUrl;
        const syncUrl = root.dataset.syncUrl;
        const storageKey = `diario_sync_queue_${diarioId}`;

        const map = L.map('map').setView([-15.78, -47.93], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const routeLine = L.polyline([], { color: '#1f6feb' }).addTo(map);
        let marker = null;

        const setStatus = (text) => {
            const el = document.getElementById('gps-status');
            if (el) {
                el.textContent = text;
            }
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
            } catch (_error) {
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
                    diario_uuid: diarioUuid,
                    device_id: navigator.userAgent.slice(0, 120),
                    actions
                })
            });

            if (response.ok) {
                localStorage.removeItem(storageKey);
                setStatus('Sincronizado');
            }
        };

        const syncBtn = document.getElementById('sync-offline');
        if (syncBtn) {
            syncBtn.addEventListener('click', syncOffline);
        }

        const startGpsBtn = document.getElementById('start-gps');
        if (startGpsBtn) {
            startGpsBtn.addEventListener('click', () => {
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
        }

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
        if (!canvas || !signatureInput) {
            return;
        }

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

        const clearBtn = document.getElementById('clear-signature');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                signatureInput.value = '';
            });
        }

        const form = document.getElementById('encerramento-form');
        if (form) {
            form.addEventListener('submit', (event) => {
                if (!signatureInput.value) {
                    event.preventDefault();
                    alert('Assine no campo de assinatura antes de encerrar.');
                }
            });
        }
    })();
</script>
