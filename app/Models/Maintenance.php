<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'type',
        'service_name',
        'business_unit',
        'workshop',
        'movement',
        'reason',
        'odometer_km',
        'origin',
        'status',
        'scheduled_for',
        'performed_at',
        'hour_meter',
        'cost',
        'vendor',
        'next_due_date',
        'next_due_hour_meter',
        'description',
    ];

    protected $casts = [
        'scheduled_for' => 'date',
        'performed_at' => 'date',
        'next_due_date' => 'date',
        'hour_meter' => 'decimal:2',
        'next_due_hour_meter' => 'decimal:2',
        'odometer_km' => 'integer',
        'cost' => 'decimal:2',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function scopeWithAlerts(Builder $query): Builder
    {
        return $query
            ->where('status', '!=', 'completed')
            ->where(function (Builder $subQuery): void {
                $subQuery
                    ->whereDate('scheduled_for', '<=', now()->toDateString())
                    ->orWhereDate('next_due_date', '<=', now()->toDateString())
                    ->orWhere(function (Builder $hourQuery): void {
                        $hourQuery
                            ->whereNotNull('next_due_hour_meter')
                            ->whereHas('machine', function (Builder $machineQuery): void {
                                $machineQuery->whereColumn('machines.hour_meter', '>=', 'maintenances.next_due_hour_meter');
                            });
                    });
            });
    }
}
