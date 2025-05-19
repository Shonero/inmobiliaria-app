<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\ProyectoInmobiliario;
use App\Models\UnidadPropiedad;
use App\Models\Cliente;

class ClienteUnidadTest extends DuskTestCase
{
    public function test_asignar_cliente_a_unidad()
    {
        // Crear datos de prueba
        $proyecto = ProyectoInmobiliario::factory()->create();
        $unidad = UnidadPropiedad::factory()->create([
            'proyecto_inmobiliario_id' => $proyecto->id,
            'estado' => 'Disponible'
        ]);
        $cliente = Cliente::factory()->create();

        $this->browse(function ($browser) use ($unidad, $cliente) {
            // Login
            $browser->visit('/login')
                    ->type('email', 'vendedor@inmobiliaria.com')
                    ->type('password', 'password')
                    ->click('button[type="submit"]');
            
            // Navegar a la unidad
            $browser->visit("/unidades-propiedad/{$unidad->id}/editar")
                    ->assertSee('Editar Unidad ' . $unidad->numero_unidad);
            
            // Asignar cliente
            $browser->select('cliente_id', $cliente->id)
                    ->select('estado', 'Vendido')
                    ->type('fecha_venta', now()->format('Y-m-d'))
                    ->click('button[type="submit"]')
                    ->assertSee('Unidad actualizada correctamente');
            
            // Verificar cambios
            $unidad->refresh();
            $this->assertEquals('Vendido', $unidad->estado);
            $this->assertEquals($cliente->id, $unidad->cliente_id);
        });
    }
}