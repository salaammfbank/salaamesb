<?php

namespace Noorfarooqy\SalaamEsb\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Noorfarooqy\LaravelOnfon\Services\DefaultService;

class SystemVerificationMiddleware extends DefaultService
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $required_system_status)
    {
        $user = $request->user();
        if ($user->force_password_reset && (!$request->is('accounts/*') && !$request->is('api/accounts/*'))) {
            if (!$user->is_system) {
                if ($request->expectsJson()) {
                    $this->setError("For security purpose you must reset your password.",);
                    return $this->is_json ? $this->getResponse() : abort(403, $this->getMessage());
                }
                return Redirect::to(route('account.profile'));
            }
        }
        if ($user->is_system == $required_system_status) {
            if ((!$request->is('api/v1/ussd/keys') && !$request->is('api/v1/esb/keys')) && (int) $required_system_status == 1) {
                //user is system user, therefore must have a private and public keys
                $has_keys = $user->ActiveEncryptionKeys->count() > 0;
                if (!$has_keys) {
                    $this->setError("The request cannot be completed. Kindly generate your encryption keys to access the request.");
                    return $this->is_json ? $this->getResponse() : abort(403, $this->getMessage());
                }
            }

            return $next($request);
        }
        $this->request = $request;
        $this->setResponseType();

        $this->setError("The request cannot be completed. System verification has failed.");
        return $this->is_json ? $this->getResponse() : abort(403, $this->getMessage());
    }
}
