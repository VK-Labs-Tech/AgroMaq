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
        Schema::create('database_connections', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nome amigável da conexão');
            $table->enum('driver', ['mysql', 'sqlsrv', 'pgsql']);
            $table->string('host');
            $table->integer('port');
            $table->string('database');
            $table->string('username');
            $table->string('password')->nullable();
            $table->boolean('tested')->default(false);
            $table->timestamp('tested_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database_connections');
    }
};
