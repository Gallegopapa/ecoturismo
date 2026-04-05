<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuarios;
use App\Models\Place;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;

class ReviewProfanityTest extends TestCase
{
    use RefreshDatabase;

    public function test_palabrota_bloquea_creacion_de_resena()
    {
        // Crear usuario
        $user = Usuarios::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'fecha_registro' => now(),
            'is_admin' => false,
        ]);

        // Crear lugar mínimo
        $place = Place::create([
            'name' => 'Lugar Test',
            'description' => 'desc',
            'location' => 'ubicacion',
        ]);

        $this->actingAs($user, 'sanctum')->postJson('/api/reviews', [
            'place_id' => $place->id,
            'rating' => 4,
            'comment' => 'una mierda de atencion, hijo de puta',
        ])->assertStatus(422)->assertJsonValidationErrors(['comment']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_resena_limpia_se_crea_correctamente()
    {
        $user = Usuarios::create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),
            'fecha_registro' => now(),
            'is_admin' => false,
        ]);

        $place = Place::create([
            'name' => 'Lugar Test 2',
            'description' => 'desc',
            'location' => 'ubicacion',
        ]);

        $this->actingAs($user, 'sanctum')->postJson('/api/reviews', [
            'place_id' => $place->id,
            'rating' => 5,
            'comment' => 'Me gustó mucho, excelente servicio',
        ])->assertStatus(201);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'place_id' => $place->id,
            'rating' => 5,
        ]);
    }
}
