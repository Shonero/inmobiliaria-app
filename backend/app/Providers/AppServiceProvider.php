<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
  
public function register()
{
    if ($this->app->environment('local')) {
        $this->app->register(DuskServiceProvider::class);
    }
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
