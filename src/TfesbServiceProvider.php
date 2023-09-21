<?php

namespace Noorfarooqy\Tfesb;

use Illuminate\Support\ServiceProvider;

class TfesbServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/tfesb.php' => config_path('tfesb.php'),
        ], 'tfesb');
        $this->loadRoutesFrom(__DIR__ . '/../routes/tfesb_api.php');
    }

    public function register()
    {
    }
}
