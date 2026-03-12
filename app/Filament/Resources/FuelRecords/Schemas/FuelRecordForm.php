<?php

namespace App\Filament\Resources\FuelRecords\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FuelRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('machine_id')
                    ->relationship('machine', 'name')
                    ->required(),
                Select::make('operator_id')
                    ->relationship('operator', 'name'),
                DateTimePicker::make('fueled_at')
                    ->required(),
                TextInput::make('hour_meter')
                    ->required()
                    ->numeric(),
                TextInput::make('liters')
                    ->required()
                    ->numeric(),
                TextInput::make('price_per_liter')
                    ->required()
                    ->numeric(),
                TextInput::make('total_cost')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('supplier'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
