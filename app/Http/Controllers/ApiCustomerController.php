<?php

namespace Noorfarooqy\Tfesb\Controllers;

use Illuminate\Http\Request;
use Noorfarooqy\Tfesb\Services\CustomerServices;

class ApiCustomerController extends Controller
{

    public function GetCustomerDetails(Request $request, CustomerServices $customerServices)
    {
        return $customerServices->GetCustomerDetails($request);
    }
}
