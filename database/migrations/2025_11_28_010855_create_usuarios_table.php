<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};