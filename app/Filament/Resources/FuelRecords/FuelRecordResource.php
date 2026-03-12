<?php

namespace App\Filament\Resources\FuelRecords;

use App\Filament\Resources\FuelRecords\Pages\CreateFuelRecord;
use App\Filament\Resources\FuelRecords\Pages\EditFuelRecord;
use App\Filament\Resources\FuelRecords\Pages\ListFuelRecords;
use App\Filament\Resources\FuelRecords\Schemas\FuelRecordForm;
use App\Filament\Resources\FuelRecords\Tables\FuelRecordsTable;
use App\Models\FuelRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FuelRecordResource extends Resource
{
    protected static ?string $model = FuelRecord::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Operações';
    protected static ?string $navigationLabel = 'Combustível';
    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-beaker';

    public static function form(Schema $schema): Schema
    {
        return FuelRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FuelRecordsTable::configure($table);
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
            'index' => ListFuelRecords::route('/'),
            'create' => CreateFuelRecord::route('/create'),
            'edit' => EditFuelRecord::route('/{record}/edit'),
        ];
    }
}
