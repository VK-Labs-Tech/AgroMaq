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
        Schema::create('diario_bordos', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pre_viagem');
            $table->string('motorista_nome');
            $table->string('veiculo_identificacao');
            $table->string('origem');
            $table->string('destino');
            $table->timestamp('iniciado_em')->nullable();
            $table->timestamp('encerrado_em')->nullable();
            $table->string('integration_status')->default('pending');
            $table->string('resumo_hash')->nullable();
            $table->timestamps();
        });

        Schema::create('diario_bordo_pre_viagens', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diario_bordo_id')->constrained('diario_bordos')->cascadeOnDelete();
            $table->text('carga_descricao')->nullable();
            $table->decimal('peso_carga_kg', 10, 2)->nullable();
            $table->timestamp('previsao_saida_em')->nullable();
            $table->unsignedTinyInteger('combustivel_percentual')->nullable();
            $table->text('observacoes')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique('diario_bordo_id');
        });

        Schema::create('diario_bordo_checklists', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diario_bordo_id')->constrained('diario_bordos')->cascadeOnDelete();
            $table->string('item');
            $table->boolean('marcado')->default(false);
            $table->text('observacao')->nullable();
            $table->timestamp('checado_em')->nullable();
            $table->timestamps();
        });

        Schema::create('diario_bordo_transitos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diario_bordo_id')->constrained('diario_bordos')->cascadeOnDelete();
            $table->string('offline_id')->nullable()->index();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('velocidade_kmh', 8, 2)->nullable();
            $table->decimal('precisao_m', 8, 2)->nullable();
            $table->timestamp('registrado_em');
            $table->timestamps();
        });

        Schema::create('diario_bordo_assinaturas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diario_bordo_id')->constrained('diario_bordos')->cascadeOnDelete();
            $table->foreignId('signed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('assinante_nome');
            $table->longText('assinatura_base64');
            $table->string('documento_hash');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('assinado_em');
            $table->timestamps();

            $table->unique('diario_bordo_id');
        });

        Schema::create('diario_bordo_sync_batches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diario_bordo_id')->nullable()->constrained('diario_bordos')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('device_id');
            $table->json('payload');
            $table->timestamp('sincronizado_em');
            $table->timestamps();
        });

        Schema::create('diario_bordo_integrations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diario_bordo_id')->constrained('diario_bordos')->cascadeOnDelete();
            $table->string('canal')->default('webhook');
            $table->string('status')->default('pending');
            $table->unsignedTinyInteger('tentativas')->default(0);
            $table->json('payload');
            $table->text('resposta')->nullable();
            $table->timestamp('processado_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diario_bordo_integrations');
        Schema::dropIfExists('diario_bordo_sync_batches');
        Schema::dropIfExists('diario_bordo_assinaturas');
        Schema::dropIfExists('diario_bordo_transitos');
        Schema::dropIfExists('diario_bordo_checklists');
        Schema::dropIfExists('diario_bordo_pre_viagens');
        Schema::dropIfExists('diario_bordos');
    }
};

