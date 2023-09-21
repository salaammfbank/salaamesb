<?php

namespace Noorfarooqy\Tfesb\Services;

use Illuminate\Support\Facades\Http;
use Noorfarooqy\LaravelOnfon\Traits\RequestHandler;

trait EsbTunnel
{

    use RequestHandler;
    public $api_url = config('tfesb.tf.endpoints.root');
    public $username = config('tfesb.tf.auth.user_name');


    public function SendPostRequest($payload): bool | Object
    {
        $endpoint = $this->api_url . '/' . config('tfesb.tf.endpoints.customer.cif');
        $response = Http::post($endpoint, $payload);

        if ($response->status() != 200) {
            $this->setError($response->json()['detail'] ?? 'Unknown error details', 'TFESB_001');
            return false;
        }

        return $response->json();
    }
}
