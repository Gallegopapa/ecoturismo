<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuarios;
use App\Models\Place;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReviewFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function createUser($isAdmin = false)
    {
        return Usuarios::create([
            'name' => 'Test User ' . Str::random(5),
            'email' => Str::random(5) . '@example.com',
            'password' => Hash::make('password'),
            'fecha_registro' => now(),
            'is_admin' => $isAdmin,
        ]);
    }

    private function createPlace()
    {
        return Place::create([
            'name' => 'Lugar Test ' . Str::random(5),
            'description' => 'desc',
            'location' => 'ubicacion',
        ]);
    }

    public function test_usuario_no_puede_resena_mismo_lugar_dos_veces()
    {
        $user = $this->createUser();
        $place = $this->createPlace();

        // Primera reseña
        $this->actingAs($user, 'sanctum')->postJson('/api/reviews', [
            'place_id' => $place->id,
            'rating' => 5,
            'comment' => 'Excelente lugar.',
        ])->assertStatus(201); // Created

        $this->assertDatabaseCount('reviews', 1);

        // Intento de segunda reseña al mismo lugar
        $this->actingAs($user, 'sanctum')->postJson('/api/reviews', [
            'place_id' => $place->id,
            'rating' => 3,
            'comment' => 'Ya comenté, pero intento de nuevo.',
        ])->assertStatus(422); // Unprocessable Entity

        // Sigue habiendo solo 1 reseña
        $this->assertDatabaseCount('reviews', 1);
    }

    public function test_usuario_puede_actualizar_su_propia_resena()
    {
        $user = $this->createUser();
        $place = $this->createPlace();

        $review = Review::create([
            'user_id' => $user->id,
            'place_id' => $place->id,
            'rating' => 4,
            'comment' => 'Muy bueno',
            'fecha_comentario' => now(),
        ]);

        $this->actingAs($user, 'sanctum')->putJson('/api/reviews/' . $review->id, [
            'rating' => 5,
            'comment' => 'Corrijo: Excelente',
            'place_id' => $place->id,
        ])->assertStatus(200);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 5,
            'comment' => 'Corrijo: Excelente',
        ]);
    }

    public function test_usuario_no_puede_editar_resena_ajena()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $place = $this->createPlace();

        $review = Review::create([
            'user_id' => $user1->id,
            'place_id' => $place->id,
            'rating' => 4,
            'comment' => 'Review de user 1',
            'fecha_comentario' => now(),
        ]);

        $this->actingAs($user2, 'sanctum')->putJson('/api/reviews/' . $review->id, [
            'rating' => 1,
            'comment' => 'Intento hackear',
            'place_id' => $place->id,
        ])->assertStatus(403);
    }

    public function test_usuario_puede_eliminar_su_propia_resena()
    {
        $user = $this->createUser();
        $place = $this->createPlace();

        $review = Review::create([
            'user_id' => $user->id,
            'place_id' => $place->id,
            'rating' => 4,
            'comment' => 'Para borrar',
            'fecha_comentario' => now(),
        ]);

        $this->actingAs($user, 'sanctum')->deleteJson('/api/reviews/' . $review->id)
            ->assertStatus(200);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_admin_puede_eliminar_cualquier_resena()
    {
        $user = $this->createUser();
        $admin = $this->createUser(true);
        $place = $this->createPlace();

        $review = Review::create([
            'user_id' => $user->id,
            'place_id' => $place->id,
            'rating' => 4,
            'comment' => 'Para borrar por admin',
            'fecha_comentario' => now(),
        ]);

        $this->actingAs($admin, 'sanctum')->deleteJson('/api/reviews/' . $review->id)
            ->assertStatus(200);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_usuario_no_puede_eliminar_resena_ajena()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $place = $this->createPlace();

        $review = Review::create([
            'user_id' => $user1->id,
            'place_id' => $place->id,
            'rating' => 4,
            'comment' => 'Review de user 1',
            'fecha_comentario' => now(),
        ]);

        $this->actingAs($user2, 'sanctum')->deleteJson('/api/reviews/' . $review->id)
            ->assertStatus(403);

        $this->assertDatabaseCount('reviews', 1);
    }
}
