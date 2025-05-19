<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\ProyectoInmobiliario;
use Facebook\WebDriver\WebDriverBy;

class UnidadPropiedadTest extends DuskTestCase
{
    public function test_agregar_unidad_a_proyecto()
    {
        // Crear proyecto de prueba
        $proyecto = ProyectoInmobiliario::factory()->create([
            'nombre' => 'Torre Central TEST'
        ]);

        $this->browse(function ($browser) use ($proyecto) {
            // Login
            $browser->visit('/login')
                    ->type('email', 'admin@inmobiliaria.com')
                    ->type('password', 'password')
                    ->click('button[type="submit"]');
            
            // Navegar al detalle del proyecto
            $browser->visit("/proyectos-inmobiliarios/{$proyecto->id}")
                    ->assertSee('Torre Central TEST');
            
            // Agregar nueva unidad
            $browser->click('@agregar-unidad-btn')
                    ->type('numero_unidad', 'A101')
                    ->select('tipo_unidad', 'Departamento')
                    ->type('metraje_cuadrado', '85.5')
                    ->type('precio_venta', '185000000')
                    ->select('estado', 'Disponible')
                    ->click('button[type="submit"]')
                    ->assertSee('Unidad agregada correctamente')
                    ->assertSee('A101');
        });
    }
}