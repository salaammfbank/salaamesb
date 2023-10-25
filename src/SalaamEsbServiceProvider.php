<?php

namespace Noorfarooqy\SalaamEsb;

use Illuminate\Support\ServiceProvider;

class SalaamEsbServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/esb_api.php');

        $this->publishes([
            __DIR__ . '/../config/salaamesb.php' => config_path('salaamesb.php'),
        ], 'esb-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations/'),
        ], 'esb-migrations');

        $this->publishes([
            __DIR__ . '/../database/seeders/' => database_path('seeders/'),
        ], 'esb-seeders');

        $this->publishes([
            __DIR__ . '/../app/Contracts/' => app_path('Contracts/'),
        ], 'esb-contracts');
    }

    public function register()
    {
    }
}
