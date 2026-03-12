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
        // Only rename if the old table exists and the new one does not to avoid collisions
        if (Schema::hasTable('connection_databases') && ! Schema::hasTable('database_connections')) {
            Schema::rename('connection_databases', 'database_connections');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename back if needed
        if (Schema::hasTable('database_connections') && ! Schema::hasTable('connection_databases')) {
            Schema::rename('database_connections', 'connection_databases');
        }
    }
};
