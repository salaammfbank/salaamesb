<?php


return [

    "tf" => [
        'auth' => [
            'user_name' => env('TF_AUTH_USERNAME', 'user'),
        ],
        'endpoints' => [
            'root' => env('TF_EP_ROOT'),
            'customer' => [
                'cif' => 'GetCustomerInfo',
                'account' => 'GetAccountInfo',
                'account.balance' => 'Query',
                'account.statement' => 'GetStatement',
                'chequebook.charges' => 'CalculateCharges',
                'chequebook.request' => 'ChequeBookRequest',
                'chequebook.status' => 'ChequeStatus',
                'exchange.rates' => 'GetExchangeRates',
                'transfer' => 'Transfer',
            ],
            'public_key' => 'GetPublicKey',
        ],
    ],

];
