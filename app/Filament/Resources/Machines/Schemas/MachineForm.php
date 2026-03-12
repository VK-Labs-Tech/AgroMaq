<?php

namespace App\Filament\Resources\Machines\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MachineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('asset_tag')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('brand')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('manufacture_year')
                    ->numeric(),
                TextInput::make('serial_number'),
                TextInput::make('plate'),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'maintenance' => 'Maintenance'])
                    ->default('active')
                    ->required(),
                TextInput::make('hour_meter')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('preventive_interval_hours')
                    ->required()
                    ->numeric()
                    ->default(250.0),
                TextInput::make('last_preventive_hour_meter')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DatePicker::make('last_preventive_date'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
