<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Cliente;

class ClienteValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_valida_si_el_rut_ya_existe()
    {
        $user = \App\Models\User::factory()->create();
        $cliente = \App\Models\Cliente::factory()->create([
            'rut' => '12345678-9'
        ]);

        $this->actingAs($user, 'api');

        $response = $this->getJson("/api/clientes/validar-rut/12345678-9");

        $response->assertStatus(200)
                 ->assertJson(['exists' => true]);
    }
}
