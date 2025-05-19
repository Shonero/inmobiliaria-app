<?php
namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\ProyectoInmobiliario;

class ProyectoInmobiliarioTest extends DuskTestCase
{
    public function test_creacion_proyecto_inmobiliario()
    {
        $this->browse(function ($browser) {
            // 1. Iniciar sesión (ajusta según tu sistema de autenticación)
            $browser->visit('/login')
                    ->type('email', 'admin@inmobiliaria.com')
                    ->type('password', 'password')
                    ->click('button[type="submit"]');
            
            // 2. Navegar al formulario de creación
            $browser->visit('/proyectos-inmobiliarios/crear')
                    ->assertSee('Nuevo Proyecto Inmobiliario');
            
            // 3. Llenar el formulario
            $browser->type('nombre', 'Residencial Las Palmas')  // Cambiado de nombre_proyecto a nombre
                   ->type('descripcion', 'Proyecto residencial de lujo en zona norte')
                   ->type('ubicacion', 'Av. Principal 123, Santiago')
                   ->type('fecha_inicio', '2023-06-01')
                   ->type('fecha_finalizacion', '2024-12-31')
                   ->select('estado', 'En construcción')
                   ->click('button[type="submit"]');
            
            // 4. Verificar redirección y mensaje de éxito
            $browser->assertPathIs('/proyectos-inmobiliarios')
                    ->assertSee('Proyecto creado exitosamente')
                    ->assertSee('Residencial Las Palmas');
            
            // 5. Verificar en base de datos
            $this->assertDatabaseHas('proyecto_inmobiliarios', [  // Corregido el nombre de la tabla
                'nombre' => 'Residencial Las Palmas',
                'estado' => 'En construcción'
            ]);
        });
    }
}
