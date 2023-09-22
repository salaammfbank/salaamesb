<?php

namespace Noorfarooqy\EnterpriseServiceBus\Controllers;

use Illuminate\Http\Request;
use Noorfarooqy\EnterpriseServiceBus\Services\CustomerServices;

class ApiCustomerController extends Controller
{

    public function GetCustomerDetails(Request $request, CustomerServices $customerServices)
    {
        return $customerServices->GetCustomerDetails($request);
    }
}
