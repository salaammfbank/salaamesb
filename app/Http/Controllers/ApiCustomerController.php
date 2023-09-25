<?php

namespace Noorfarooqy\SalaamEsb\Controllers;

use Illuminate\Http\Request;
use Noorfarooqy\SalaamEsb\Services\CustomerServices;

class ApiCustomerController extends Controller
{

    public function GetCustomerDetails(Request $request, CustomerServices $customerServices)
    {
        return $customerServices->GetCustomerCifDetails($request);
    }
}
