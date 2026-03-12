<?php

namespace App\Filament\Resources\DatabaseConnections;

use App\Filament\Resources\DatabaseConnections\Pages\CreateConnection;
use App\Filament\Resources\DatabaseConnections\Pages\EditConnection;
use App\Filament\Resources\DatabaseConnections\Pages\ListConnections;
use App\Filament\Resources\DatabaseConnections\Schemas\ConnectionForm;
use App\Filament\Resources\DatabaseConnections\Tables\ConnectionsTable;
use App\Models\Database\ConnectionDatabase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DatabaseConnectionResource extends Resource
{
    protected static ?string $model = ConnectionDatabase::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Operações';
    protected static ?string $navigationLabel = 'Conexões DB';
    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-server';

    public static function form(Schema $schema): Schema
    {
        return ConnectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConnectionsTable::configure($table);
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
            'index' => ListConnections::route('/'),
            'create' => CreateConnection::route('/create'),
            'edit' => EditConnection::route('/{record}/edit'),
        ];
    }
}
