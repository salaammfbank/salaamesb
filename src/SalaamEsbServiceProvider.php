<?php

namespace Noorfarooqy\SalaamEsb;

use Illuminate\Support\ServiceProvider;

class SalaamEsbServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/salaamesb.php' => config_path('salaamesb.php'),
        ], 'salaamesb');
        $this->loadRoutesFrom(__DIR__ . '/../routes/esb_api.php');
    }

    public function register()
    {
    }
}
