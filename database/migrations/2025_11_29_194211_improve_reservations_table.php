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
        Schema::table('reservations', function (Blueprint $table) {
            $table->dateTime('fecha_reserva')->nullable()->after('user_id');
            $table->date('fecha_visita')->nullable()->after('fecha_reserva');
            $table->time('hora_visita')->nullable()->after('fecha_visita');
            $table->string('telefono_contacto', 20)->nullable()->after('personas');
            $table->text('comentarios')->nullable()->after('telefono_contacto');
            $table->decimal('precio_total', 10, 2)->nullable()->after('comentarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['fecha_reserva', 'fecha_visita', 'hora_visita', 'telefono_contacto', 'comentarios', 'precio_total']);
        });
    }
};
