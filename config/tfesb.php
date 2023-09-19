<?php


return [

    "tf" => [
        'auth' => [
            'user_name' => env('tf_auth_user_name', 'user'),
        ],
        'endpoints' => [
            'root' => env('tf_ep_root'),
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
