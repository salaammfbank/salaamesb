<?php

namespace Noorfarooqy\SalaamEsb\Http\Controllers;

use Illuminate\Http\Request;
use Noorfarooqy\SalaamEsb\Services\AuthenticationServices;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function registerView()
    {
        return view('auth.register');
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * operationId="apiLogin",
     * tags={"Login"},
     * summary="User Login",
     * description="Login User Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function apiLogin(Request $request, AuthenticationServices $authenticationServices)
    {
        return $authenticationServices->login($request);
    }

    public function apiLoginHr(Request $request, AuthenticationServices $authenticationServices)
    {
        return $authenticationServices->loginHr($request);
    }

    public function apiRegister(Request $request, AuthenticationServices $authenticationServices)
    {
        return $authenticationServices->register($request);
    }
}
