<?php

namespace Noorfarooqy\SalaamEsb\Http\Controllers;

use Illuminate\Http\Request;
use Noorfarooqy\LaravelOnfon\Services\DefaultService;
use Noorfarooqy\SalaamEsb\Services\UserServices;

class AccountController extends DefaultService
{

    public function ResetProfilePassword(Request $request, UserServices $userServices)
    {
        return $userServices->ResetProfilePassword($request);
    }

    public function UpdateProfile(Request $request, UserServices $userServices)
    {
        return $userServices->UpdateProfile($request);
    }
}
