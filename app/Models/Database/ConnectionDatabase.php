<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ConnectionDatabase extends Model
{
    // The migration creates the table 'database_connections', so map the model to it
    protected $table = 'database_connections';

    protected $fillable = [
        'name',
        'driver',
        'host',
        'port',
        'database',
        'username',
        'password',
        'tested',
        'tested_at',
    ];

    protected $casts = [
        'tested'    => 'boolean',
        'tested_at' => 'datetime',
        'password'  => 'encrypted',
    ];

    public function getDefaultPort(): int
    {
        return match($this->driver) {
            'mysql'  => 3306,
            'sqlsrv' => 1433,
            'pgsql'  => 5432,
            default  => 3306,
        };
    }
}
