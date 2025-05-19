<?php

namespace Tests\Functional;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use App\Models\ProyectoInmobiliario;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UnidadesTest extends TestCase
{

    use DatabaseTransactions;

    public function testAgregarUnidadAProyecto()
    {
        // Crear proyecto de prueba con datos válidos
        $proyecto = ProyectoInmobiliario::factory()->create([
            'nombre' => 'Torre Central Test',
            'descripcion' => 'Edificio de oficinas corporativas',
            'ubicacion' => 'Santiago Centro',
            'estado' => 'En construcción'
        ]);
        
        // 1. Iniciar sesión
        $this->login('gestor@inmobiliaria.com', 'password');
        
        // 2. Ir al detalle del proyecto
        $this->driver->get($this->baseUrl . "/proyectos/{$proyecto->id}");
        
        // Esperar a que cargue la página
        $this->driver->wait()->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::id('agregar-unidad-btn')
            )
        );
        
        // 3. Hacer clic en "Agregar Unidad"
        $this->driver->findElement(WebDriverBy::id('agregar-unidad-btn'))->click();
        
        // 4. Esperar a que cargue el formulario
        $this->driver->wait()->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::name('numero_unidad')
            )
        );
        
        // 5. Llenar formulario de unidad
        $this->driver->findElement(WebDriverBy::name('numero_unidad'))
            ->sendKeys('A101');
            
        $selectTipo = $this->driver->findElement(WebDriverBy::name('tipo_unidad'));
        $selectTipo->findElement(WebDriverBy::xpath("//option[. = 'Departamento']"))->click();
        
        $this->driver->findElement(WebDriverBy::name('metraje_cuadrado'))
            ->sendKeys('75.5');
            
        $this->driver->findElement(WebDriverBy::name('precio_venta'))
            ->sendKeys('185000000');
            
        $selectEstado = $this->driver->findElement(WebDriverBy::name('estado'));
        $selectEstado->findElement(WebDriverBy::xpath("//option[. = 'Disponible']"))->click();
        
        // 6. Enviar formulario
        $this->driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
        
        // 7. Verificar mensaje de éxito
        $this->driver->wait()->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::className('alert-success')
            )
        );
        
        $this->assertStringContainsString(
            'Unidad agregada correctamente',
            $this->driver->findElement(WebDriverBy::className('alert-success'))->getText()
        );
    }
}