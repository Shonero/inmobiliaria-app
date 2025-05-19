<?php

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;

class LoginSeleniumTest extends TestCase
{
    protected $driver;

    protected function setUp(): void
    {
        $options = new ChromeOptions();
        $options->addArguments([
            '--headless',
            '--disable-gpu',
            '--window-size=1920,1080'
        ]);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->driver = RemoteWebDriver::create(
            'http://localhost:9515',
            $capabilities
        );
    }

    public function test_can_access_login_page()
    {
        $this->driver->get('http://host.docker.internal:8000/login');

        $title = $this->driver->getTitle();
        $this->assertStringContainsString('Login', $title); // Ajusta según tu título real
    }

    protected function tearDown(): void
    {
        $this->driver->quit();
    }
}
