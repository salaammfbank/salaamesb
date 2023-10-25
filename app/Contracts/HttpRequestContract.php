<?php

namespace Noorfarooqy\SalaamEsb\Contracts;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait HttpRequestContract
{

    public function sendRequest($is_local = false): mixed
    {

        $response  = [];
        try {
            if ($this->method == 'POST') {
                $response = $this->PostRequest();
            } else {
                $response = $this->GetRequest();
            }

            if ($response->failed()) {
                $this->message = $response->status();
                Log::channel('i365_logs')->critical(
                    json_encode(
                        [
                            'message' => $this->message,
                            'method' => $this->method,
                            'endpoint' => $this->endpoint,
                            'payload' => $this->payload,
                            'response' => $response
                        ]
                    )
                );
            } else {
                $body = $response->json();
                // Log::info('--------------------------------');
                // Log::info($body);
                // Log::info('--------------------------------');
                if ($is_local && $body['error_code'] != 0) {
                    $this->message = $body['error_message'];

                    Log::channel('i365_logs')->critical(
                        json_encode(
                            [
                                'message' => $this->message,
                                'method' => $this->method,
                                'endpoint' => $this->endpoint,
                                'payload' => $this->payload,
                                'response' => $response
                            ]
                        )
                    );
                }
            }

            return $response;
        } catch (\Throwable $th) {
            $this->message = env('APP_DEBUG') ? $th->getMessage() : ErrorCodes::failed_http_request;

            Log::channel('agent_slack')->critical(
                json_encode(
                    [
                        'message' => $this->message,
                        'method' => $this->method,
                        'endpoint' => $this->endpoint,
                        'payload' => $this->payload,
                        'response' => $response
                    ]
                )
            );
        }

        return false;
    }

    public function parseEndpoint(string $endpoint, bool $is_local = false): string
    {
        if ($is_local)
            return env('APP_ESB_URL') . '/' . $endpoint;
        return $endpoint;
    }

    public function PostRequest(): \Illuminate\Http\Client\Response
    {
        $authorization = [
            'username' => env('TAJ_USERNAME'),
            'password' => hash('sha512', env('TAJ_PASSWORD'))
        ];
        $authorization = json_encode($authorization);
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // 'Authorization' => 'Bearer ' . $authorization,
        ])->withBasicAuth(env('TAJ_USERNAME'), env('TAJ_PASSWORD'))->withOptions(['verify' => false])
            ->post($this->parseEndpoint($this->endpoint), $this->payload);

        return $response;
    }

    public function GetRequest(): \Illuminate\Http\Client\Response
    {
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            // 'Authorization' => 'Bearer ' . env('APP_ESB_TOKEN'),
        ])->withOptions(['verify' => false])
            ->get($this->parseEndpoint($this->endpoint));

        return $response;
    }
}
