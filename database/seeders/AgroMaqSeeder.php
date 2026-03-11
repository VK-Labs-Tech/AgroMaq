<?php

namespace Database\Seeders;

use App\Models\FuelRecord;
use App\Models\Machine;
use App\Models\Maintenance;
use App\Models\Operator;
use App\Models\WorkLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AgroMaqSeeder extends Seeder
{
    public function run(): void
    {
        $machines = collect([
            [
                'name' => 'Trator JD 6110J',
                'asset_tag' => 'AGRO-TR-001',
                'type' => 'Trator',
                'brand' => 'John Deere',
                'model' => '6110J',
                'manufacture_year' => 2020,
                'serial_number' => 'JD6110JBR2020',
                'plate' => 'MQR1A10',
                'status' => 'active',
                'hour_meter' => 2680,
                'preventive_interval_hours' => 250,
                'last_preventive_hour_meter' => 2450,
                'last_preventive_date' => '2025-11-18',
            ],
            [
                'name' => 'Colheitadeira Case 8230',
                'asset_tag' => 'AGRO-CO-002',
                'type' => 'Colheitadeira',
                'brand' => 'Case IH',
                'model' => '8230',
                'manufacture_year' => 2019,
                'serial_number' => 'CASE8230BR2019',
                'plate' => 'MQR2B30',
                'status' => 'maintenance',
                'hour_meter' => 4120,
                'preventive_interval_hours' => 300,
                'last_preventive_hour_meter' => 3800,
                'last_preventive_date' => '2025-10-03',
            ],
            [
                'name' => 'Pulverizador Uniport 3030',
                'asset_tag' => 'AGRO-PV-003',
                'type' => 'Pulverizador',
                'brand' => 'Jacto',
                'model' => 'Uniport 3030',
                'manufacture_year' => 2021,
                'serial_number' => 'UNI3030BR2021',
                'plate' => 'MQR3C40',
                'status' => 'active',
                'hour_meter' => 1985,
                'preventive_interval_hours' => 200,
                'last_preventive_hour_meter' => 1880,
                'last_preventive_date' => '2026-01-12',
            ],
            [
                'name' => 'Caminhao Atego 2430',
                'asset_tag' => 'AGRO-CM-004',
                'type' => 'Caminhao',
                'brand' => 'Mercedes',
                'model' => 'Atego 2430',
                'manufacture_year' => 2018,
                'serial_number' => 'ATEGO2430BR2018',
                'plate' => 'MQR4D50',
                'status' => 'active',
                'hour_meter' => 3260,
                'preventive_interval_hours' => 350,
                'last_preventive_hour_meter' => 3000,
                'last_preventive_date' => '2025-12-20',
            ],
        ])->map(fn (array $machine) => Machine::query()->create($machine));

        $operators = collect([
            [
                'name' => 'Carlos Souza',
                'cpf' => '111.222.333-44',
                'phone' => '(11) 99888-7766',
                'license_number' => 'CNH123456',
                'license_category' => 'D',
                'license_expires_at' => '2026-09-30',
                'active' => true,
            ],
            [
                'name' => 'Ana Ribeiro',
                'cpf' => '555.666.777-88',
                'phone' => '(11) 97766-5544',
                'license_number' => 'CNH456789',
                'license_category' => 'C',
                'license_expires_at' => '2026-04-10',
                'active' => true,
            ],
            [
                'name' => 'Paulo Mendes',
                'cpf' => '999.111.222-33',
                'phone' => '(11) 96655-4433',
                'license_number' => 'CNH987654',
                'license_category' => 'E',
                'license_expires_at' => '2027-01-15',
                'active' => true,
            ],
        ])->map(fn (array $operator) => Operator::query()->create($operator));

        $this->seedWorkLogs($machines, $operators);
        $this->seedFuelRecords($machines, $operators);
        $this->seedMaintenances($machines);
    }

    private function seedWorkLogs($machines, $operators): void
    {
        $sessions = [
            [$machines[0], $operators[0], 2640, 2662, 10],
            [$machines[0], $operators[1], 2662, 2674, 6],
            [$machines[1], $operators[2], 4090, 4104, 8],
            [$machines[1], $operators[2], 4104, 4116, 4],
            [$machines[2], $operators[1], 1950, 1963, 7],
            [$machines[2], $operators[0], 1963, 1978, 3],
            [$machines[3], $operators[2], 3225, 3242, 5],
            [$machines[3], $operators[0], 3242, 3258, 2],
        ];

        foreach ($sessions as [$machine, $operator, $start, $end, $daysAgo]) {
            $startedAt = Carbon::now()->subDays($daysAgo)->setTime(7, 30);
            $endedAt = (clone $startedAt)->addHours((int) max($end - $start, 1));

            WorkLog::query()->create([
                'machine_id' => $machine->id,
                'operator_id' => $operator->id,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
                'start_hour_meter' => $start,
                'end_hour_meter' => $end,
                'hours_worked' => round($end - $start, 2),
                'activity' => 'Operacao de campo e deslocamento interno.',
            ]);
        }
    }

    private function seedFuelRecords($machines, $operators): void
    {
        $records = [
            [$machines[0], $operators[0], 2674, 180, 6.25, 5],
            [$machines[1], $operators[2], 4116, 240, 6.45, 4],
            [$machines[2], $operators[1], 1978, 130, 6.10, 3],
            [$machines[3], $operators[2], 3258, 210, 6.35, 2],
        ];

        foreach ($records as [$machine, $operator, $hourMeter, $liters, $price, $daysAgo]) {
            FuelRecord::query()->create([
                'machine_id' => $machine->id,
                'operator_id' => $operator->id,
                'fueled_at' => Carbon::now()->subDays($daysAgo)->setTime(18, 0),
                'hour_meter' => $hourMeter,
                'liters' => $liters,
                'price_per_liter' => $price,
                'total_cost' => round($liters * $price, 2),
                'supplier' => 'Posto Rural Central',
                'notes' => 'Abastecimento apos expediente.',
            ]);
        }
    }

    private function seedMaintenances($machines): void
    {
        Maintenance::query()->create([
            'machine_id' => $machines[0]->id,
            'type' => 'preventive',
            'service_name' => 'Troca de oleo e filtros',
            'status' => 'completed',
            'scheduled_for' => '2025-11-18',
            'performed_at' => '2025-11-18',
            'hour_meter' => 2450,
            'cost' => 1250,
            'vendor' => 'Oficina Agro Service',
            'next_due_date' => '2026-04-01',
            'next_due_hour_meter' => 2700,
            'description' => 'Preventiva de rotina com verificacao geral.',
        ]);

        Maintenance::query()->create([
            'machine_id' => $machines[0]->id,
            'type' => 'preventive',
            'service_name' => 'Revisao de 250 horas',
            'status' => 'overdue',
            'scheduled_for' => now()->subDays(3)->toDateString(),
            'hour_meter' => 2690,
            'cost' => 0,
            'vendor' => 'Agro Service',
            'next_due_hour_meter' => 2950,
            'description' => 'Revisao pendente por horimetro e data.',
        ]);

        Maintenance::query()->create([
            'machine_id' => $machines[1]->id,
            'type' => 'corrective',
            'service_name' => 'Reparo do sistema hidraulico',
            'status' => 'in_progress',
            'scheduled_for' => now()->toDateString(),
            'hour_meter' => 4120,
            'cost' => 4850,
            'vendor' => 'Tecno Agro Mecanica',
            'description' => 'Substituicao de mangueiras e calibracao.',
        ]);

        Maintenance::query()->create([
            'machine_id' => $machines[2]->id,
            'type' => 'preventive',
            'service_name' => 'Troca de bicos de pulverizacao',
            'status' => 'scheduled',
            'scheduled_for' => now()->addDays(6)->toDateString(),
            'hour_meter' => 1985,
            'cost' => 980,
            'vendor' => 'Jacto Partner',
            'next_due_date' => now()->addMonths(4)->toDateString(),
            'next_due_hour_meter' => 2080,
            'description' => 'Ajuste preventivo para eficiencia de aplicacao.',
        ]);

        Maintenance::query()->create([
            'machine_id' => $machines[3]->id,
            'type' => 'corrective',
            'service_name' => 'Alinhamento e balanceamento',
            'status' => 'completed',
            'scheduled_for' => now()->subDays(8)->toDateString(),
            'performed_at' => now()->subDays(7)->toDateString(),
            'hour_meter' => 3245,
            'cost' => 1450,
            'vendor' => 'Pneus Forte',
            'description' => 'Servico concluido sem pendencias.',
        ]);
    }
}
