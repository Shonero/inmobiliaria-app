<?php

namespace Tests\Functional;

use App\Models\ProyectoInmobiliario;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class ProyectosTest extends TestCase
{
    public function testCreacionProyectoInmobiliario()
    {
        try {
            // 1. Iniciar sesión
            $this->driver->get($this->baseUrl.'/login');
            
            $this->driver->wait(10)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(
                    WebDriverBy::name('email')
                )
            );
            
            $this->driver->findElement(WebDriverBy::name('email'))
                ->sendKeys('admin@inmobiliaria.com');
                
            $this->driver->findElement(WebDriverBy::name('password'))
                ->sendKeys('password');
                
            $this->driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))
                ->click();
                
            // 2. Navegar a creación de proyectos
            $this->driver->get($this->baseUrl.'/proyectos/crear');
            
            // 3. Llenar formulario
            $this->driver->findElement(WebDriverBy::name('nombre_proyecto'))
                ->sendKeys('Residencial Selenium');
                
            // ... completar otros campos ...
                
            $this->driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))
                ->click();
                
            // 4. Verificar creación
            $this->driver->wait(10)->until(
                WebDriverExpectedCondition::titleContains('Proyectos')
            );
            
            $this->assertStringContainsString(
                'Residencial Selenium',
                $this->driver->getPageSource()
            );
            
        } catch (\Exception $e) {
            $this->captureFailures();
            throw $e;
        }
    }
}