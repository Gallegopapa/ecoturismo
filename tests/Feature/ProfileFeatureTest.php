<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Pruebas de Feature para Perfil de Usuario
 * Cubre: obtener perfil, actualizar datos, cambiar contraseña y eliminar cuenta.
 */
class ProfileFeatureTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────────────────────
    // Helper
    // ─────────────────────────────────────────────────────────────

    private function createUser(array $overrides = []): Usuarios
    {
        return Usuarios::create(array_merge([
            'name'           => 'usuario' . Str::random(4),
            'email'          => Str::random(5) . '@gmail.com',
            'password'       => Hash::make('Password1!'),
            'fecha_registro' => now(),
            'is_admin'       => false,
        ], $overrides));
    }

    // ─────────────────────────────────────────────────────────────
    // VER PERFIL
    // ─────────────────────────────────────────────────────────────

    /** El usuario autenticado puede ver su perfil. */
    public function test_usuario_autenticado_puede_ver_perfil()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'sanctum')
             ->getJson('/api/profile')
             ->assertStatus(200)
             ->assertJsonStructure([
                 'user' => ['id', 'name', 'email', 'telefono', 'foto_perfil', 'fecha_registro', 'is_admin'],
             ])
             ->assertJsonFragment([
                 'id'    => $user->id,
                 'name'  => $user->name,
                 'email' => $user->email,
             ]);
    }

    /** Un invitado no puede ver el perfil. */
    public function test_invitado_no_puede_ver_perfil()
    {
        $this->getJson('/api/profile')
             ->assertStatus(401);
    }

    // ─────────────────────────────────────────────────────────────
    // ACTUALIZAR PERFIL
    // ─────────────────────────────────────────────────────────────

    /** El usuario puede actualizar su número de teléfono. */
    public function test_usuario_puede_actualizar_numero_telefono()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile', [
                 'telefono' => '3001234567',
             ])
             ->assertStatus(200)
             ->assertJsonFragment(['telefono' => '3001234567']);

        $this->assertDatabaseHas('usuarios', [
            'id'       => $user->id,
            'telefono' => '3001234567',
        ]);
    }

    /** El usuario puede cambiar su nombre de usuario a uno único. */
    public function test_usuario_puede_actualizar_nombre_usuario()
    {
        $user    = $this->createUser();
        $newName = 'nuevonombre' . Str::random(3);

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile', [
                 'name' => $newName,
             ])
             ->assertStatus(200)
             ->assertJsonFragment(['name' => $newName]);

        $this->assertDatabaseHas('usuarios', ['id' => $user->id, 'name' => $newName]);
    }

    /** No se puede usar un nombre que ya existe. */
    public function test_usuario_no_puede_usar_nombre_duplicado()
    {
        $other = $this->createUser(['name' => 'nombretomado']);
        $user  = $this->createUser();

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile', ['name' => $other->name])
             ->assertStatus(422)
             ->assertJsonValidationErrorFor('name');
    }

    /** El usuario puede actualizar su correo a uno de @gmail.com no registrado. */
    public function test_usuario_puede_actualizar_correo()
    {
        $user     = $this->createUser();
        $newEmail = 'nuevo' . Str::random(4) . '@gmail.com';

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile', [
                 'email' => $newEmail,
             ])
             ->assertStatus(200)
             ->assertJsonFragment(['email' => strtolower($newEmail)]);
    }

    /** No se puede cambiar el correo a uno que no sea @gmail.com. */
    public function test_usuario_no_puede_usar_correo_no_gmail()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile', [
                 'email' => 'otro@hotmail.com',
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrorFor('email');
    }

    /** No se puede usar un correo que ya existe en otro usuario. */
    public function test_usuario_no_puede_usar_correo_duplicado()
    {
        $other = $this->createUser();
        $user  = $this->createUser();

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile', ['email' => $other->email])
             ->assertStatus(422)
             ->assertJsonValidationErrorFor('email');
    }

    /** Un invitado no puede actualizar el perfil. */
    public function test_invitado_no_puede_actualizar_perfil()
    {
        $this->putJson('/api/profile', ['telefono' => '3001234567'])
             ->assertStatus(401);
    }

    // ─────────────────────────────────────────────────────────────
    // CAMBIAR CONTRASEÑA
    // ─────────────────────────────────────────────────────────────

    /** El usuario puede cambiar su contraseña con la actual correcta. */
    public function test_usuario_puede_cambiar_contrasena()
    {
        $user = $this->createUser(['password' => Hash::make('Actual123!')]);

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile/password', [
                 'current_password'      => 'Actual123!',
                 'new_password'          => 'Nueva456!',
                 'new_password_confirmation' => 'Nueva456!',
             ])
             ->assertStatus(200)
             ->assertJsonFragment(['message' => 'Contraseña actualizada correctamente.']);

        // Verificar que la nueva contraseña fue persistida
        $this->assertTrue(Hash::check('Nueva456!', $user->fresh()->password));
    }

    /** La contraseña actual incorrecta es rechazada. */
    public function test_cambio_contrasena_falla_con_contrasena_actual_incorrecta()
    {
        $user = $this->createUser(['password' => Hash::make('Actual123!')]);

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile/password', [
                 'current_password'          => 'PasswordMalEscrita!',
                 'new_password'              => 'Nueva456!',
                 'new_password_confirmation' => 'Nueva456!',
             ])
             ->assertStatus(422);
    }

    /** No se puede usar la misma contraseña como nueva. */
    public function test_cambio_contrasena_falla_si_es_igual_a_la_actual()
    {
        $user = $this->createUser(['password' => Hash::make('Actual123!')]);

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile/password', [
                 'current_password'          => 'Actual123!',
                 'new_password'              => 'Actual123!',
                 'new_password_confirmation' => 'Actual123!',
             ])
             ->assertStatus(422);
    }

    /** Nueva contraseña muy corta (< 6 chars) es rechazada. */
    public function test_cambio_contrasena_rechaza_contrasena_muy_corta()
    {
        $user = $this->createUser(['password' => Hash::make('Actual123!')]);

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile/password', [
                 'current_password'          => 'Actual123!',
                 'new_password'              => 'abc',
                 'new_password_confirmation' => 'abc',
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrorFor('new_password');
    }

    /** Nueva contraseña sin confirmación coincidente es rechazada. */
    public function test_cambio_contrasena_rechaza_confirmacion_diferente()
    {
        $user = $this->createUser(['password' => Hash::make('Actual123!')]);

        $this->actingAs($user, 'sanctum')
             ->putJson('/api/profile/password', [
                 'current_password'          => 'Actual123!',
                 'new_password'              => 'Nueva456!',
                 'new_password_confirmation' => 'Diferente789!',
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrorFor('new_password');
    }

    /** Un invitado no puede cambiar contraseñas. */
    public function test_invitado_no_puede_cambiar_contrasena()
    {
        $this->putJson('/api/profile/password', [
            'current_password'          => 'algo',
            'new_password'              => 'nueva',
            'new_password_confirmation' => 'nueva',
        ])->assertStatus(401);
    }

    // ─────────────────────────────────────────────────────────────
    // ELIMINAR CUENTA
    // ─────────────────────────────────────────────────────────────

    /** El usuario puede eliminar su propia cuenta. */
    public function test_usuario_puede_eliminar_su_cuenta()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'sanctum')
             ->deleteJson('/api/profile')
             ->assertStatus(200)
             ->assertJsonFragment(['message' => 'Cuenta eliminada exitosamente.']);

        $this->assertDatabaseMissing('usuarios', ['id' => $user->id]);
    }

    /** Un invitado no puede eliminar cuentas. */
    public function test_invitado_no_puede_eliminar_cuenta()
    {
        $this->deleteJson('/api/profile')
             ->assertStatus(401);
    }
}
