<?php

namespace Noorfarooqy\SalaamEsb\Services;

use Illuminate\Support\Facades\Hash;
use Noorfarooqy\LaravelOnfon\Services\DefaultService;
use Noorfarooqy\SalaamEsb\Models\User;

class AuthenticationServices extends DefaultService
{

    public $has_failed =  true;
    public function login($request)
    {
        $this->request = $request;
        $this->setResponseType();
        $this->rules = [
            'email' => 'required|email|max:125',
            'password' => 'required|string|max:125',
        ];

        $this->CustomValidate();

        if ($this->has_failed) {
            $this->setError($this->getMessage(), ErrorEnums::ESB_001->name, [ErrorEnums::ESB_001]);
            return $this->getResponse();
        }

        $data = $this->ValidatedData();
        $user = $user = User::where('email', $data['email'])->first();
        $is_authentic = Hash::check($data['password'], $user?->password);
        if ($is_authentic) {
            $user = User::where('email', $data['email'])->first();
            $token = $this->createUserToken($user);
            $resp = [
                'user' => $user,
                'api_token' => $token->plainTextToken, // TO DO Set the scope for the token using user permissions
            ];

            $this->setError('', 0);
            $this->setSuccess('success');
            return  $this->getResponse($resp);
        }

        $this->setError($m = "User email and password do not match ", ErrorEnums::ESB_001->name, [ErrorEnums::ESB_001]);
        //TO DO record auth failures
        return $this->getResponse();
    }

    public function loginHr($request)
    {
        $this->request = $request;
        $this->setResponseType();
        $this->rules = [
            'username' => 'required|email|max:125',
            'password' => 'required|string|max:125',
        ];

        $this->CustomValidate();

        if ($this->has_failed) {
            $this->setError($this->getMessage(), ErrorEnums::ESB_001->name, [ErrorEnums::ESB_001]);
            return  $this->getResponse();
        }

        $data = $this->ValidatedData();
        $user = $user = User::where('email', $data['username'])->first();
        $is_authentic = Hash::check($data['password'], $user?->password);
        if ($is_authentic) {
            $user = User::where('email', $data['username'])->first();
            $token = $this->createUserToken($user);
            $resp = [
                'api_token' => $token->plainTextToken, // TO DO Set the scope for the token using user permissions
                'token_type' => 'bearer',
                'expires_in' => 60 * 60 * 60 * 24,
                'userName' => [
                    'issued' => now()->format('Y-m-d H:i:s'),
                    'expires' => now()->addMinutes(60 * 24)->format('Y-m-d H:i:s'),
                ],
            ];

            return $resp;
        }

        $this->setError($m = "User email and password do not match ", ErrorEnums::ESB_001->name, [ErrorEnums::ESB_001]);
        //TO DO record auth failures
        return $this->is_json ? $this->getResponse() : false;
    }

    public function register($request)
    {
        $this->request = $request;
        $this->setResponseType();
        $this->rules = [
            'name' => 'required|string|max:45',
            'email' => 'required|email|max:125|regex:/(.*)@salaammfbank\.co\.ke/i|unique:users',
            'password' => 'required|string|max:125|confirmed',
        ];

        $this->CustomValidate();

        if ($this->has_failed) {
            $this->setError($this->getMessage(), ErrorEnums::ESB_001->name, [ErrorEnums::ESB_001]);
            return $this->is_json ? $this->getResponse() : false;
        }

        $data = $this->ValidatedData();
        $data['password'] = Hash::make($data['password']);

        try {
            $user = User::create($data);

            $token = $this->createUserToken($user);
            $resp = [
                'user' => $user,
                'api_token' => $token->plainTextToken, // TO DO Set the scope for the token using user permissions
            ];

            return $this->is_json ? $this->getResponse($resp) : $resp;
        } catch (\Throwable $th) {
            $this->setError($m = env('APP_DEBUG') ? $th->getMessage() : 'Oops! Something went wrong. Could not create user', ErrorEnums::ESB_001->name, [ErrorEnums::ESB_001]);
            return $this->is_json ? $this->getResponse() : false;
        }
    }

    public function createUserToken($user)
    {
        //TO DO set user token scope
        return $user?->createToken('auth_token');
    }
}


enum ErrorEnums: string
{
    case ESB_001 = 'Default Error description';
    case ESB_002 = 'SMS - Invalid format number ';

    public static function getErrorDescription($error, $other_errors = [])
    {
        if (!$error) {
            return '';
        }

        $cases = count($other_errors) > 0 ? array_merge($other_errors, static::cases()) : static::cases();
        foreach ($cases as $key => $case) {
            if ($case->name == $error) {
                return $case->value;
            }
        }

        return 'Uknown error description';
    }
}
