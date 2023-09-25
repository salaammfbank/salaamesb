<?php

namespace Noorfarooqy\SalaamEsb\Services;

use Noorfarooqy\LaravelOnfon\Services\DefaultService;
use Noorfarooqy\SalaamEsb\Traits\EsbTunnelTrait as TraitsEsbTunnelTrait;

class CoreBankingServices extends DefaultService
{
    use TraitsEsbTunnelTrait;
    public function GetCustomerDetails($request)
    {
        $this->request = $request;

        $this->rules = [
            'account_type' => 'required|numeric',
            'account_number' => 'required|numeric',
            'branch_code' => 'required|numeric',
        ];

        $this->customValidate();

        if ($this->has_failed) {
            return $this->getResponse();
        }

        $data = $this->validatedData();

        $this->SetEndpoints();
        $payload = [

            "referenceNumber" => time(),
            "userName" => $this->username,
            "userPassword" => "l5OEJszcwvIKcKyts0vb7Zc/QNJOfiunfVR8QMCOsTMbB5mR3oHtVCmt4l44+fBEOkhFShZnOvoTR1jxHIaTGKwweYRqtlKySwJuKjTdhh5H+Zr3EPex3FlTS5rDzcrO15hNdxPurt77Jf+VNYp8OOtYaYvlkv+05sl0Qh8+zzdmeAPM2zDJjYpnfu2r2bhraJnGflp1gEjZC38yzwCNxga9mcYzy729kaqJWQyx/0UmDDhEs3DM6yB08ZI1xbihwyZtBKnAKcpGEnbSFI1QPzKvomSz/5htzerD+kKEuvjVtI7bPISpK9SxD6x1mbEMJyo8J3zVEy+x047JZCXfXw==",
            "branchCode" => $data["branch_code"],
            "accountType" => $data["account_type"],
            "accountNumber" => $data["account_number"],

        ];

        $response = $this->SendPostRequest($payload);

        return $response;
    }

    public function QueryCustomerAccounts($payload)
    {
        return true;
    }
}
