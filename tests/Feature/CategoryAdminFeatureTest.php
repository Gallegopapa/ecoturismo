<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuarios;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CategoryAdminFeatureTest extends TestCase
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

    public function test_admin_puede_ver_categorias()
    {
        $admin = $this->createUser(true);
        
        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/categories')
            ->assertStatus(200); 
    }

    public function test_usuario_normal_no_puede_crear_categorias()
    {
        $user = $this->createUser(false);
        
        $this->actingAs($user, 'sanctum')
            ->postJson('/api/categories', [
                'name' => 'Test',
                'description' => 'Test desc',
            ])
            ->assertStatus(403);
    }

    public function test_admin_puede_crear_categoria()
    {
        $admin = $this->createUser(true);

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/categories', [
                'name' => 'Nueva Categoría Test',
                'description' => 'Descripción de la categoría test',
                'icon' => 'fas fa-tree',
            ])
            ->assertStatus(201); // Created

        $this->assertDatabaseHas('categories', [
            'name' => 'Nueva Categoría Test',
        ]);
    }

    public function test_admin_puede_actualizar_categoria()
    {
        $admin = $this->createUser(true);
        
        $category = Category::create([
            'name' => 'Categoría Original',
            'slug' => 'categoria-original',
            'icon' => 'fa-icon',
        ]);

        $this->actingAs($admin, 'sanctum')
            ->putJson('/api/categories/' . $category->id, [
                'name' => 'Categoría Modificada',
                'description' => 'Nueva desc',
                'icon' => 'fas fa-new',
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Categoría Modificada',
        ]);
    }

    public function test_admin_puede_eliminar_categoria()
    {
        $admin = $this->createUser(true);
        
        $category = Category::create([
            'name' => 'Categoría para Borrar',
            'slug' => 'categoria-borrar',
        ]);

        $this->actingAs($admin, 'sanctum')
            ->deleteJson('/api/categories/' . $category->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
