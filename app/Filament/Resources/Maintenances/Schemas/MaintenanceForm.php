<?php

namespace App\Filament\Resources\Maintenances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MaintenanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('machine_id')
                    ->relationship('machine', 'name')
                    ->required(),
                Select::make('type')
                    ->options(['preventive' => 'Preventive', 'corrective' => 'Corrective'])
                    ->required(),
                TextInput::make('service_name')
                    ->required(),
                TextInput::make('business_unit'),
                TextInput::make('workshop'),
                TextInput::make('movement'),
                TextInput::make('reason'),
                TextInput::make('odometer_km')
                    ->numeric(),
                TextInput::make('origin'),
                Select::make('status')
                    ->options([
            'scheduled' => 'Scheduled',
            'in_progress' => 'In progress',
            'completed' => 'Completed',
            'overdue' => 'Overdue',
        ])
                    ->default('scheduled')
                    ->required(),
                DatePicker::make('scheduled_for'),
                DatePicker::make('performed_at'),
                TextInput::make('hour_meter')
                    ->numeric(),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('vendor'),
                DatePicker::make('next_due_date'),
                TextInput::make('next_due_hour_meter')
                    ->numeric(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
