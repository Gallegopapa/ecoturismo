<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuarios;
use App\Models\Place;
use App\Models\PlaceSchedule;
use App\Models\Reservation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function createUser()
    {
        return Usuarios::create([
            'name' => 'Test User ' . Str::random(5),
            'email' => Str::random(5) . '@example.com',
            'password' => Hash::make('password'),
            'fecha_registro' => now(),
            'is_admin' => false,
        ]);
    }

    private function createPlace()
    {
        $place = Place::create([
            'name' => 'Lugar Test Reservas ' . Str::random(5),
            'description' => 'desc',
            'location' => 'ubicacion',
        ]);

        // Crear horario disponible lunes a viernes de 08:00 a 18:00
        $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
        foreach ($dias as $dia) {
            PlaceSchedule::create([
                'place_id' => $place->id,
                'dia_semana' => $dia,
                'hora_inicio' => '08:00',
                'hora_fin' => '18:00',
                'activo' => true,
            ]);
        }

        return $place;
    }

    public function test_usuario_puede_ver_sus_reservas()
    {
        $user = $this->createUser();
        $place = $this->createPlace();
        
        Reservation::create([
            'user_id' => $user->id,
            'place_id' => $place->id,
            'fecha_reserva' => now(),
            'fecha_visita' => now()->next('Monday')->format('Y-m-d'),
            'fecha' => now()->next('Monday')->format('Y-m-d'),
            'hora_visita' => '10:00',
            'personas' => 2,
            'estado' => 'pendiente',
        ]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/reservations/my')
            ->assertStatus(200);
    }

    public function test_usuario_puede_crear_reserva_valida()
    {
        $user = $this->createUser();
        $place = $this->createPlace();
        
        $nextMonday = now()->next('Monday')->format('Y-m-d');
        
        $this->actingAs($user, 'sanctum')
            ->postJson('/api/reservations', [
                'place_id' => $place->id,
                'fecha_visita' => $nextMonday,
                'hora_visita' => '10:00',
                'personas' => 4,
                'telefono_contacto' => '1234567890',
            ])
            ->assertStatus(201); // Created

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'place_id' => $place->id,
            'hora_visita' => '10:00',
            'personas' => 4,
        ]);
    }

    public function test_usuario_no_puede_reservar_fuera_de_horario()
    {
        $user = $this->createUser();
        $place = $this->createPlace();
        
        $nextSunday = now()->next('Sunday')->format('Y-m-d'); // Domingo está cerrado
        
        $this->actingAs($user, 'sanctum')
            ->postJson('/api/reservations', [
                'place_id' => $place->id,
                'fecha_visita' => $nextSunday,
                'hora_visita' => '10:00',
                'personas' => 4,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['fecha_visita']);

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_usuario_no_puede_reservar_en_horario_solapado()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $place = $this->createPlace();
        
        $nextMonday = now()->next('Monday')->format('Y-m-d');
        
        // Primera reserva
        $this->actingAs($user1, 'sanctum')
            ->postJson('/api/reservations', [
                'place_id' => $place->id,
                'fecha_visita' => $nextMonday,
                'hora_visita' => '10:00',
                'personas' => 2,
                'telefono_contacto' => '1234567890',
            ])->assertStatus(201);
        
        // Intentar reservar a las 11:00 el mismo día (se solapa)
        $this->actingAs($user2, 'sanctum')
            ->postJson('/api/reservations', [
                'place_id' => $place->id,
                'fecha_visita' => $nextMonday,
                'hora_visita' => '11:00',
                'personas' => 4,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['hora_visita']);
            
        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_usuario_puede_eliminar_su_propia_reserva()
    {
        $user = $this->createUser();
        $place = $this->createPlace();
        
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'place_id' => $place->id,
            'fecha_reserva' => now(),
            'fecha_visita' => now()->next('Monday')->format('Y-m-d'),
            'fecha' => now()->next('Monday')->format('Y-m-d'),
            'hora_visita' => '10:00',
            'personas' => 2,
            'estado' => 'pendiente',
        ]);
        
        $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/reservations/' . $reservation->id)
            ->assertStatus(200);
            
        $this->assertDatabaseMissing('reservations', [
            'id' => $reservation->id,
        ]);
    }
}
