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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('place_id');
            $table->tinyInteger('rating')->unsigned()->comment('Calificación de 1 a 5');
            $table->text('comment')->nullable();
            $table->dateTime('fecha_comentario')->default(now());
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            $table->unique(['user_id', 'place_id']); // Un usuario solo puede comentar una vez por lugar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
