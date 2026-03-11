<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'license_number',
        'license_category',
        'license_expires_at',
        'active',
        'notes',
    ];

    protected $casts = [
        'license_expires_at' => 'date',
        'active' => 'boolean',
    ];

    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class);
    }

    public function fuelRecords(): HasMany
    {
        return $this->hasMany(FuelRecord::class);
    }

    public function totalWorkedHours(): float
    {
        return (float) $this->workLogs()->sum('hours_worked');
    }
}
