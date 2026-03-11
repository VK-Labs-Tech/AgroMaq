<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
            $table->foreignId('operator_id')->constrained()->restrictOnDelete();
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->decimal('start_hour_meter', 10, 2);
            $table->decimal('end_hour_meter', 10, 2);
            $table->decimal('hours_worked', 10, 2);
            $table->text('activity')->nullable();
            $table->timestamps();

            $table->index(['machine_id', 'started_at']);
            $table->index(['operator_id', 'started_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};
