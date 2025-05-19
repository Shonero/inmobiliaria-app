<?php

namespace Tests\Functional;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected RemoteWebDriver $driver;
    protected string $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->baseUrl = env('APP_URL', 'http://webserver');
        $host = 'http://selenium-hub:4444/wd/hub';
        
        $this->driver = RemoteWebDriver::create(
            $host,
            DesiredCapabilities::chrome()
        );
        
        $this->driver->manage()->window()->maximize();
    }

    protected function tearDown(): void
    {
        if (isset($this->driver)) {
            $this->driver->quit();
        }
        parent::tearDown();
    }

    protected function captureFailures(): void
    {
        if (isset($this->driver) && $this->hasFailed()) {
            $screenshotDir = storage_path('logs/screenshots');
            if (!file_exists($screenshotDir)) {
                mkdir($screenshotDir, 0777, true);
            }
            $filename = sprintf('%s_%s.png', class_basename($this), time());
            $this->driver->takeScreenshot("{$screenshotDir}/{$filename}");
        }
    }
}