<?php

namespace App\Filament\Resources\DatabaseConnections\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ConnectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name'),
            Select::make('driver')
                ->options([
                    'mysql' => 'MySQL',
                    'pgsql' => 'PostgreSQL',
                    'sqlsrv' => 'SQL Server',
                ])
                ->required(),
            TextInput::make('host')->required(),
            TextInput::make('port')->numeric(),
            TextInput::make('database')->required(),
            TextInput::make('username'),
            TextInput::make('password')->password(),
            Toggle::make('tested'),
        ]);
    }
}
