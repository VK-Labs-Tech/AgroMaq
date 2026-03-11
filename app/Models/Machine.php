<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'asset_tag',
        'type',
        'brand',
        'model',
        'manufacture_year',
        'serial_number',
        'plate',
        'status',
        'hour_meter',
        'preventive_interval_hours',
        'last_preventive_hour_meter',
        'last_preventive_date',
        'notes',
    ];

    protected $casts = [
        'manufacture_year' => 'integer',
        'hour_meter' => 'decimal:2',
        'preventive_interval_hours' => 'decimal:2',
        'last_preventive_hour_meter' => 'decimal:2',
        'last_preventive_date' => 'date',
    ];

    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class);
    }

    public function fuelRecords(): HasMany
    {
        return $this->hasMany(FuelRecord::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function nextPreventiveHourMeter(): float
    {
        return (float) $this->last_preventive_hour_meter + (float) $this->preventive_interval_hours;
    }

    public function hoursUntilPreventive(): float
    {
        return round($this->nextPreventiveHourMeter() - (float) $this->hour_meter, 2);
    }

    public function needsPreventiveMaintenance(): bool
    {
        return $this->hoursUntilPreventive() <= 0;
    }

    public function totalWorkedHours(): float
    {
        return (float) $this->workLogs()->sum('hours_worked');
    }

    public function totalFuelCost(): float
    {
        return (float) $this->fuelRecords()->sum('total_cost');
    }

    public function totalMaintenanceCost(): float
    {
        return (float) $this->maintenances()->sum('cost');
    }

    public function operationalCost(): float
    {
        return round($this->totalFuelCost() + $this->totalMaintenanceCost(), 2);
    }

    public function operationalCostPerHour(): float
    {
        $totalHours = $this->totalWorkedHours();

        if ($totalHours <= 0) {
            return 0.0;
        }

        return round($this->operationalCost() / $totalHours, 2);
    }
}
