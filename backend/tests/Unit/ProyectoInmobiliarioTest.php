<?php

namespace Tests\Unit;

namespace Tests\Feature;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProyectoInmobiliarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_puede_crear_proyecto()
{
    // Crear usuario de prueba
    $user = User::factory()->create();

    // Generar token JWT
    $token = JWTAuth::fromUser($user);

    // Datos del proyecto
    $payload = [
        'nombre' => 'Edificio Horizonte',
        'descripcion' => 'Proyecto de departamentos',
        'ubicacion' => 'Santiago Centro',
        'fecha_inicio' => '2025-01-01',
        'fecha_fin' => '2025-12-31',
        'estado' => 'En construcción',
    ];

    // Llamar al endpoint con el token en el header Authorization
    $response = $this->withHeader('Authorization', "Bearer $token")
                     ->postJson('/api/proyectos', $payload);

    // Verificar respuesta esperada
    $response->assertStatus(201)
             ->assertJsonFragment([
                 'nombre' => 'Edificio Horizonte',
             ]);
}
}
