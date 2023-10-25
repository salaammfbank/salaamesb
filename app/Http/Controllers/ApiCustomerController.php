<?php

namespace Noorfarooqy\SalaamEsb\Http\Controllers;

use Illuminate\Http\Request;
use Noorfarooqy\SalaamEsb\Services\CustomerServices;
use Noorfarooqy\SalaamEsb\Services\OnboardingServices;

class ApiCustomerController extends Controller
{

    public function GetCustomerDetails(Request $request, CustomerServices $customerServices)
    {
        return $customerServices->GetCustomerCifDetails($request);
    }


    public function OnboardCustomer(Request $request, OnboardingServices $onboardingServices)
    {
        return $onboardingServices->OnboardCustomer($request);
    }
}
