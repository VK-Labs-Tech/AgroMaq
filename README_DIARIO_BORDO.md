# Diario de Bordo Agroindustrial

Modulo implementado em Laravel + Blade com arquitetura em camadas:

- Controllers: `app/Http/Controllers/Admin/DiarioBordoController.php`, `DiarioBordoSyncController.php`
- Requests: `app/Http/Requests/DiarioBordo/*`
- Services: `app/Services/*`
- Repositories e Interfaces: `app/Repositories/Contracts/DiarioBordoRepositoryInterface.php`, `app/Repositories/Eloquent/DiarioBordoRepository.php`
- Views: `resources/views/admin/diario_bordo/*`

## Fluxo de 4 etapas

1. Pre-viagem
2. Checklist
3. Registro em transito com GPS em tempo real
4. Encerramento com assinatura digital (canvas)

## Offline sync

- Pontos de GPS que falham sao armazenados no `localStorage` por diario.
- Botao "Sincronizar offline" envia lote para `POST /admin/diario-bordo/sync`.

## Integracoes automaticas

- Eventos de transicao e encerramento sao enfileirados via `ProcessDiarioBordoIntegrationJob`.
- Configure `DIARIO_BORDO_WEBHOOK_URL` no `.env` para habilitar webhook.

## Rotas principais (admin)

- `GET /admin/diario-bordo`
- `GET /admin/diario-bordo/novo`
- `GET /admin/diario-bordo/{id}`
- `POST /admin/diario-bordo/{id}/pre-viagem`
- `POST /admin/diario-bordo/{id}/checklist`
- `POST /admin/diario-bordo/{id}/transito`
- `POST /admin/diario-bordo/{id}/encerramento`
- `POST /admin/diario-bordo/sync`

