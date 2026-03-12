<?php

namespace App\Filament\Resources\DatabaseConnections\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ConnectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome')->sortable()->searchable(),
                TextColumn::make('driver')->label('Driver')->sortable(),
                TextColumn::make('host')->label('Host'),
                TextColumn::make('database')->label('DB'),
                IconColumn::make('tested')->boolean()->label('Testado'),
            ])
            ->actions([
                \Filament\Actions\Action::make('test')
                    ->label('Testar')
                    ->color('success')
                    ->icon('heroicon-o-play')
                    ->action(function ($record) {
                        $config = [
                            'driver' => $record->driver,
                            'host' => $record->host,
                            'port' => $record->port,
                            'database' => $record->database,
                            'username' => $record->username,
                            'password' => $record->password,
                            'charset' => 'utf8mb4',
                            'prefix' => '',
                        ];

                        // If using SQL Server, set encryption/trust options for ODBC Driver 18
                        if (($record->driver ?? '') === 'sqlsrv') {
                            $config['encrypt'] = 'yes';
                            $config['trust_server_certificate'] = true;
                        }

                        Config::set('database.connections.temp_connection', $config);

                        try {
                            DB::connection('temp_connection')->getPdo();

                            // fetch tables depending on driver
                            $driver = $record->driver;
                            $connection = DB::connection('temp_connection');
                            $tables = [];

                            switch ($driver) {
                                case 'mysql':
                                    $rows = $connection->select('SHOW TABLES');
                                    $tables = array_map(fn($r) => array_values((array)$r)[0], $rows ?: []);
                                    break;
                                case 'pgsql':
                                    $rows = $connection->select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema'");
                                    $tables = array_map(fn($r) => $r->tablename, $rows ?: []);
                                    break;
                                case 'sqlsrv':
                                    $rows = $connection->select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE='BASE TABLE' AND TABLE_CATALOG = ?", [$record->database]);
                                    $tables = array_map(fn($r) => $r->TABLE_NAME, $rows ?: []);
                                    break;
                            }

                            // mark as tested
                            $record->update([
                                'tested' => true,
                                'tested_at' => now(),
                            ]);

                            $count = count($tables);
                            $body = $count ? implode('\n', $tables) : 'Nenhuma tabela encontrada.';

                            \Filament\Notifications\Notification::make()
                                ->title('Conexão válida')
                                ->success()
                                ->body("Tabelas encontradas (" . $count . "):\n" . $body)
                                ->send();

                        } catch (\Throwable $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Falha na conexão')
                                ->danger()
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),

                \Filament\Actions\Action::make('import')
                    ->label('Importar')
                    ->color('secondary')
                    ->modalHeading('Importar tabelas da conexão')
                    ->form([
                        \Filament\Forms\Components\CheckboxList::make('tables')
                            ->required()
                            ->options(fn ($record) => self::fetchRemoteTables($record)),
                        \Filament\Forms\Components\Select::make('mode')
                            ->label('Modo')
                            ->options([
                                'schema' => 'Apenas estrutura (criar tabelas locais)',
                                'schema_data' => 'Estrutura e dados (criar e importar)',
                            ])
                            ->default('schema')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $tables = $data['tables'] ?? [];
                        $mode = $data['mode'] ?? 'schema';

                        if (empty($tables)) {
                            \Filament\Notifications\Notification::make()
                                ->title('Nenhuma tabela selecionada')
                                ->warning()
                                ->send();
                            return;
                        }

                        $config = [
                            'driver' => $record->driver,
                            'host' => $record->host,
                            'port' => $record->port,
                            'database' => $record->database,
                            'username' => $record->username,
                            'password' => $record->password,
                            'charset' => 'utf8mb4',
                            'prefix' => '',
                        ];

                        if (($record->driver ?? '') === 'sqlsrv') {
                            $config['encrypt'] = 'yes';
                            $config['trust_server_certificate'] = true;
                        }

                        Config::set('database.connections.temp_connection', $config);

                        foreach ($tables as $tableName) {
                            try {
                                $columns = self::fetchRemoteColumns($record, $tableName);

                                if (empty($columns)) continue;

                                if (! Schema::hasTable($tableName)) {
                                    Schema::create($tableName, function (Blueprint $table) use ($columns) {
                                        $hasPrimary = false;

                                        foreach ($columns as $col) {
                                            $name = $col['COLUMN_NAME'];
                                            $type = strtolower($col['DATA_TYPE']);
                                            $nullable = ($col['IS_NULLABLE'] ?? 'NO') === 'YES';
                                            $length = $col['CHARACTER_MAXIMUM_LENGTH'] ?? null;
                                            $default = $col['COLUMN_DEFAULT'] ?? null;

                                            // simple mappings
                                            switch (true) {
                                                case str_contains($type, 'int'):
                                                    $colObj = $table->integer($name);
                                                    break;
                                                case $type === 'bigint':
                                                    $colObj = $table->unsignedBigInteger($name);
                                                    break;
                                                case in_array($type, ['varchar','nvarchar','character varying','text','ntext']):
                                                    $colObj = $length ? $table->string($name, $length) : $table->text($name);
                                                    break;
                                                case in_array($type, ['datetime','timestamp','smalldatetime']):
                                                    $colObj = $table->dateTime($name);
                                                    break;
                                                case $type === 'date':
                                                    $colObj = $table->date($name);
                                                    break;
                                                case in_array($type, ['decimal','numeric']):
                                                    $colObj = $table->decimal($name, $col['NUMERIC_PRECISION'] ?? 10, $col['NUMERIC_SCALE'] ?? 0);
                                                    break;
                                                case in_array($type, ['float','double']):
                                                    $colObj = $table->float($name);
                                                    break;
                                                case in_array($type, ['tinyint','bit']):
                                                    $colObj = $table->boolean($name);
                                                    break;
                                                default:
                                                    $colObj = $table->text($name);
                                                    break;
                                            }

                                            if ($nullable) $colObj->nullable();

                                            $noDefaultTypes = ['text','tinytext','mediumtext','longtext','blob','tinyblob','mediumblob','longblob','geometry','json'];
                                            if (! is_null($default) && ! in_array($type, $noDefaultTypes, true)) {
                                                $colObj->default($default);
                                            }
                                        }
                                    });
                                }

                                if ($mode === 'schema_data') {
                                    // Import data in chunks
                                    DB::connection('temp_connection')->table($tableName)
                                        ->chunk(500, function ($rows) use ($tableName) {
                                            $insert = [];
                                            foreach ($rows as $r) {
                                                $insert[] = (array) $r;
                                            }

                                            if (! empty($insert)) {
                                                // insert into local DB
                                                DB::table($tableName)->insert($insert);
                                            }
                                        });
                                }

                            } catch (\Throwable $e) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Erro ao importar ' . $tableName)
                                    ->danger()
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Importação finalizada')
                            ->success()
                            ->body('Tabelas importadas: ' . count($tables))
                            ->send();
                    }),

                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }

    protected static function fetchRemoteTables($record): array
    {
        $config = [
            'driver' => $record->driver,
            'host' => $record->host,
            'port' => $record->port,
            'database' => $record->database,
            'username' => $record->username,
            'password' => $record->password,
            'charset' => 'utf8mb4',
            'prefix' => '',
        ];

        if (($record->driver ?? '') === 'sqlsrv') {
            $config['encrypt'] = 'yes';
            $config['trust_server_certificate'] = true;
        }

        Config::set('database.connections.temp_connection', $config);

        try {
            $conn = DB::connection('temp_connection');
            $driver = $record->driver;

            switch ($driver) {
                case 'mysql':
                    $rows = $conn->select('SHOW TABLES');
                    return array_combine(array_map(fn($r) => array_values((array)$r)[0], $rows ?: []), array_map(fn($r) => array_values((array)$r)[0], $rows ?: []));
                case 'pgsql':
                    $rows = $conn->select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema'");
                    return array_combine(array_map(fn($r) => $r->tablename, $rows ?: []), array_map(fn($r) => $r->tablename, $rows ?: []));
                case 'sqlsrv':
                    $rows = $conn->select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE='BASE TABLE' AND TABLE_CATALOG = ?", [$record->database]);
                    return array_combine(array_map(fn($r) => $r->TABLE_NAME, $rows ?: []), array_map(fn($r) => $r->TABLE_NAME, $rows ?: []));
                default:
                    return [];
            }
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected static function fetchRemoteColumns($record, string $tableName): array
    {
        $conn = DB::connection('temp_connection');
        $driver = $record->driver;

        switch ($driver) {
            case 'mysql':
                $dbName = $record->database;
                $rows = $conn->select("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE FROM information_schema.columns WHERE table_schema = ? AND table_name = ?", [$dbName, $tableName]);
                return array_map(fn($r) => (array) $r, $rows ?: []);
            case 'pgsql':
                $dbName = $record->database;
                $rows = $conn->select("SELECT column_name AS COLUMN_NAME, data_type AS DATA_TYPE, is_nullable AS IS_NULLABLE, column_default AS COLUMN_DEFAULT, character_maximum_length AS CHARACTER_MAXIMUM_LENGTH, numeric_precision AS NUMERIC_PRECISION, numeric_scale AS NUMERIC_SCALE FROM information_schema.columns WHERE table_catalog = ? AND table_name = ?", [$dbName, $tableName]);
                return array_map(fn($r) => (array) $r, $rows ?: []);
            case 'sqlsrv':
                $dbName = $record->database;
                $rows = $conn->select("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_CATALOG = ? AND TABLE_NAME = ?", [$dbName, $tableName]);
                return array_map(fn($r) => (array) $r, $rows ?: []);
            default:
                return [];
        }
    }
}
