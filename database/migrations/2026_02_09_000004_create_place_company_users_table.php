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
        Schema::create('place_company_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('company_user_id');
            $table->enum('rol', ['gerente', 'recepcionista', 'admin'])->default('gerente');
            $table->boolean('es_principal')->default(false);
            $table->timestamps();

            // Claves foráneas
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            $table->foreign('company_user_id')->references('id')->on('usuarios')->onDelete('cascade');

            // Índices
            $table->index('place_id');
            $table->index('company_user_id');
            $table->unique(['place_id', 'company_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_company_users');
    }
};
