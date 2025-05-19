<?php

namespace Tests\Feature;
use App\Models\User;
use Tests\TestCase;
use App\Models\ProyectoInmobiliario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;


class ProyectoTest extends TestCase
{
    use RefreshDatabase;

 public function test_se_puede_listar_proyectos()
{
    $user = User::factory()->create();

    // Genera token JWT para ese usuario
$token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

    ProyectoInmobiliario::factory()->count(5)->create();

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->getJson('/api/proyectos');

   $response->assertJsonCount(5, 'data');

}

   public function test_se_puede_crear_proyecto()
{
    $user = User::factory()->create();

    $data = [
        'nombre' => 'Proyecto Test',
        'descripcion' => 'Descripción Test',
        'ubicacion' => 'Ubicación Test',
        'fecha_inicio' => '2025-01-01',
        'fecha_fin' => '2025-12-31',
        'estado' => 'En construcción',
    ];

    $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

    $response = $this->postJson('/api/proyectos', $data, [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(201)
             ->assertJsonFragment(['nombre' => 'Proyecto Test']);
}
}
