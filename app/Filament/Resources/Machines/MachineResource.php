<?php

namespace App\Filament\Resources\Machines;

use App\Filament\Resources\Machines\Pages\CreateMachine;
use App\Filament\Resources\Machines\Pages\EditMachine;
use App\Filament\Resources\Machines\Pages\ListMachines;
use App\Filament\Resources\Machines\Schemas\MachineForm;
use App\Filament\Resources\Machines\Tables\MachinesTable;
use App\Models\Machine;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MachineResource extends Resource
{
    protected static ?string $model = Machine::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Frota';
    protected static ?string $navigationLabel = 'Máquinas';
    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return MachineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MachinesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMachines::route('/'),
            'create' => CreateMachine::route('/create'),
            'edit' => EditMachine::route('/{record}/edit'),
        ];
    }
}
