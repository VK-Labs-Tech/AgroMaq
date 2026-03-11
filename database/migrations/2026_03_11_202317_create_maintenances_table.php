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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['preventive', 'corrective']);
            $table->string('service_name');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'overdue'])->default('scheduled');
            $table->date('scheduled_for')->nullable();
            $table->date('performed_at')->nullable();
            $table->decimal('hour_meter', 10, 2)->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->string('vendor')->nullable();
            $table->date('next_due_date')->nullable();
            $table->decimal('next_due_hour_meter', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['status', 'scheduled_for']);
            $table->index(['machine_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
