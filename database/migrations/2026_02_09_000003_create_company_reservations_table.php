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
        Schema::create('company_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('company_user_id');
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('rejection_reason_id')->nullable();
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
            $table->text('comentario_rechazo')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('company_user_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            $table->foreign('rejection_reason_id')->references('id')->on('rejection_reasons')->onDelete('set null');

            // Índices para búsquedas rápidas
            $table->index('company_user_id');
            $table->index('place_id');
            $table->index('estado');
            $table->unique(['reservation_id', 'place_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_reservations');
    }
};
