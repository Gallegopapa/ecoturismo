<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RejectionReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            [
                'code' => 'no_disponibilidad',
                'descripcion' => 'No hay disponibilidad en esa fecha',
                'detalles' => 'El lugar no está disponible para la fecha y hora solicitada.',
            ],
            [
                'code' => 'capacidad_excedida',
                'descripcion' => 'La capacidad ha sido excedida',
                'detalles' => 'El número de personas excede la capacidad máxima del lugar.',
            ],
            [
                'code' => 'error_datos',
                'descripcion' => 'Datos incompletos o incorrectos',
                'detalles' => 'Los datos proporcionados en la reserva tienen errores o están incompletos.',
            ],
            [
                'code' => 'mantenimiento',
                'descripcion' => 'En mantenimiento durante esa fecha',
                'detalles' => 'El lugar está en mantenimiento o cierre programado en esa fecha.',
            ],
            [
                'code' => 'evento_privado',
                'descripcion' => 'Evento privado programado',
                'detalles' => 'El lugar está reservado para un evento privado en esa fecha.',
            ],
            [
                'code' => 'otro',
                'descripcion' => 'Otro motivo',
                'detalles' => 'Por favor, proporcione detalles adicionales en el comentario.',
            ],
        ];

        foreach ($reasons as $reason) {
            DB::table('rejection_reasons')->updateOrInsert(
                ['code' => $reason['code']],
                [
                    'descripcion' => $reason['descripcion'],
                    'detalles' => $reason['detalles'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
