<?php

namespace App\Filament\Resources\WorkLogs;

use App\Filament\Resources\WorkLogs\Pages\CreateWorkLog;
use App\Filament\Resources\WorkLogs\Pages\EditWorkLog;
use App\Filament\Resources\WorkLogs\Pages\ListWorkLogs;
use App\Filament\Resources\WorkLogs\Schemas\WorkLogForm;
use App\Filament\Resources\WorkLogs\Tables\WorkLogsTable;
use App\Models\WorkLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class WorkLogResource extends Resource
{
    protected static ?string $model = WorkLog::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Operações';
    protected static ?string $navigationLabel = 'Horas trabalhadas';
    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-clock';

    public static function form(Schema $schema): Schema
    {
        return WorkLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkLogsTable::configure($table);
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
            'index' => ListWorkLogs::route('/'),
            'create' => CreateWorkLog::route('/create'),
            'edit' => EditWorkLog::route('/{record}/edit'),
        ];
    }
}
