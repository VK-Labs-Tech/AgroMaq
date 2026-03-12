<?php

namespace App\Filament\Resources\WorkLogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class WorkLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('machine_id')
                    ->relationship('machine', 'name')
                    ->required(),
                Select::make('operator_id')
                    ->relationship('operator', 'name')
                    ->required(),
                DateTimePicker::make('started_at')
                    ->required(),
                DateTimePicker::make('ended_at')
                    ->required(),
                TextInput::make('start_hour_meter')
                    ->required()
                    ->numeric(),
                TextInput::make('end_hour_meter')
                    ->required()
                    ->numeric(),
                TextInput::make('hours_worked')
                    ->required()
                    ->numeric(),
                Textarea::make('activity')
                    ->columnSpanFull(),
            ]);
    }
}
