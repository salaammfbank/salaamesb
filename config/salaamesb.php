<?php


return [

    "bank_code" => "",
    "sbu" => [
        'auth' => [
            'user_name' => env('SBU_AUTH_USERNAME', 'user'),//Salaam Bank Uganda username
        ],
        'endpoints' => [
            'root' => env('SBU_EP_ROOT'), // Salaam Bank Uganda root endpoint
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
