<?php

namespace Tests;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class BrowserTestCase extends BaseTestCase
{
   protected function setUp(): void
{
    parent::setUp();
    
    // Ejecutar seeders para datos básicos
    \Artisan::call('db:seed', ['--class' => 'EstadoProyectosSeeder']);
    \Artisan::call('db:seed', ['--class' => 'TiposUnidadSeeder']);
    
    // Configurar el tamaño de la pantalla
    $this->browser->manage()->window()->setSize(new \Facebook\WebDriver\WebDriverDimension(1920, 1080));
}

    public static function prepare()
    {
        // static::startChromeDriver();
    }

    protected function driver()
    {
        $host = 'http://selenium-hub:4444/wd/hub';
        
        return RemoteWebDriver::create(
            $host,
            DesiredCapabilities::chrome()
        );
    }
}