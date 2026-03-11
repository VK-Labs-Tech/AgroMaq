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
        Schema::table('maintenances', function (Blueprint $table) {
            $table->string('business_unit')->nullable()->after('service_name');
            $table->string('workshop')->nullable()->after('business_unit');
            $table->string('movement')->nullable()->after('workshop');
            $table->string('reason')->nullable()->after('movement');
            $table->unsignedInteger('odometer_km')->nullable()->after('reason');
            $table->string('origin')->nullable()->after('odometer_km');

            $table->index(['type', 'business_unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropIndex(['type', 'business_unit']);
            $table->dropColumn([
                'business_unit',
                'workshop',
                'movement',
                'reason',
                'odometer_km',
                'origin',
            ]);
        });
    }
};
