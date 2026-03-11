<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'operator_id',
        'fueled_at',
        'hour_meter',
        'liters',
        'price_per_liter',
        'total_cost',
        'supplier',
        'notes',
    ];

    protected $casts = [
        'fueled_at' => 'datetime',
        'hour_meter' => 'decimal:2',
        'liters' => 'decimal:2',
        'price_per_liter' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }
}
