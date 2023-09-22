<?php

namespace Noorfarooqy\EnterpriseServiceBus;

use Illuminate\Support\ServiceProvider;

class TfesbServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/esb.php' => config_path('esb.php'),
        ], 'esb');
        $this->loadRoutesFrom(__DIR__ . '/../routes/esb_api.php');
    }

    public function register()
    {
    }
}
