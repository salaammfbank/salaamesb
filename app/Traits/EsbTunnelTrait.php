<?php

namespace Noorfarooqy\SalaamEsb\Traits;

use Illuminate\Support\Facades\Http;
use Noorfarooqy\LaravelOnfon\Traits\RequestHandler;

trait EsbTunnelTrait
{

    use RequestHandler;
    public $api_url;
    public $username;


    public function SendPostRequest($payload, $api): bool | Object | array
    {
        $this->SetEndpoints();
        $endpoint = $this->api_url . '/' . $api;
        $response = Http::post($endpoint, $payload);

        if ($response->status() != 200) {
            $this->setError($response->json() ?? 'Unknown error details', 'ESB_001');
            return false;
        }

        return $response->json();
    }

    private function SetEndpoints()
    {
        $this->api_url = config('salaamesb.sbu.endpoints.root');
        $this->username = config('salaamesb.sbu.auth.user_name');
    }
}
