<?php


namespace Noorfarooqy\Tfesb\Services;

use Noorfarooqy\LaravelOnfon\Services\DefaultService;

class CustomerServices extends DefaultService
{
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

        $response = [
            "timestamp" => "2019-03-06 10:22:55",
            "branchCCode" => "01",
            "custIId" => 11510,
            "custCName" => "الصندوقالقوميللتأمينالاجتماعي",
            "custCPphone" => "0183775815",
            "mobileCNo" => "0912161386",
            "custAccounts" => [
                [
                    "branchCCode" => "01",
                    "actCType" => "20101",
                    "custINo" => 3535,
                    "currencyCCode" => "001",
                    "openDate" => "2005-06-19",
                    "actCName" => "الصندوقالقومىللتأمينالاجتماعى ",
                    "actCOcccode" => "إعتبارى",
                    "actCOpmode" => "بالاصالة",
                ]

            ],
        ];

        $this->setError('', 0);
        $this->setSuccess('success');
        return $this->getResponse($response);
    }
}
