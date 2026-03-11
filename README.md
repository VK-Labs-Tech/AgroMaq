# AgroMaq - Gestao de Maquinas Agricolas

Sistema web completo em Laravel para acompanhar uso da frota agricola com foco em:
- horas trabalhadas
- operador responsavel
- consumo de combustivel
- manutencao preventiva e corretiva
- alertas de manutencao
- historico de servicos
- custo operacional por maquina

## Stack
- PHP 8.2+
- Laravel 12 (2026)
- SQLite (padrao para desenvolvimento)
- Blade + Tailwind CSS 4

## Modulos implementados
- Dashboard com indicadores da frota e alertas de manutencao
- Cadastro completo de maquinas
- Cadastro de operadores
- Registro de horas trabalhadas por maquina e operador
- Registro de abastecimentos com calculo automatico de custo
- Manutencoes com status, vencimento e historico
- Relatorio de custo operacional por maquina com filtro por periodo

## Regras de negocio
- Horas trabalhadas calculadas automaticamente (horimetro)
- Custo de abastecimento calculado automaticamente (litros x preco/litro)
- Horimetro da maquina atualizado automaticamente por uso/abastecimento/manutencao
- Manutencao vencida identificada por data e por horimetro limite
- Custo operacional por maquina = combustivel + manutencao
- Custo por hora = custo operacional / horas trabalhadas

## Como executar
1. Instale dependencias:
   - composer install
2. Configure ambiente:
   - copy .env.example .env
   - php artisan key:generate
3. Rode banco e seed:
   - php artisan migrate:fresh --seed
4. Gere os assets frontend (obrigatorio para nao ocorrer erro de manifest):
   - npm install
   - npm run build
5. Inicie servidor:
   - php artisan serve
6. Acesse:
   - http://127.0.0.1:8000

## Dados de exemplo
O seeder AgroMaqSeeder cria:
- 4 maquinas
- 3 operadores
- sessoes de trabalho
- abastecimentos
- manutencoes (incluindo vencidas e concluidas)

## Rotas principais
- / (dashboard)
- /machines
- /operators
- /work-logs
- /fuel-records
- /maintenances
- /reports/operational-costs

## Estrutura
- app/Models: entidades do dominio
- app/Http/Controllers: logica de CRUD e relatorios
- database/migrations: esquema de banco
- database/seeders: dados de exemplo
- resources/views: interface completa do sistema
