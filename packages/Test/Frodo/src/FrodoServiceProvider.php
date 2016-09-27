<?php

namespace Test\Frodo;

use Illuminate\Support\ServiceProvider;

class FrodoServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/frodo.php', 'frodo');
        include __DIR__.'/Http/routes.php';  
    }

    public function boot()
    { 
    }
}
