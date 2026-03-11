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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('asset_tag')->unique();
            $table->string('type');
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('manufacture_year')->nullable();
            $table->string('serial_number')->nullable()->unique();
            $table->string('plate')->nullable()->unique();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->decimal('hour_meter', 10, 2)->default(0);
            $table->decimal('preventive_interval_hours', 10, 2)->default(250);
            $table->decimal('last_preventive_hour_meter', 10, 2)->default(0);
            $table->date('last_preventive_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
