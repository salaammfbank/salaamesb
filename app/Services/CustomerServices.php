<?php


namespace Noorfarooqy\EnterpriseServiceBus\Services;

use Noorfarooqy\LaravelOnfon\Services\DefaultService;
use Noorfarooqy\EnterpriseServiceBus\Traits\EsbTunnelTrait;
use Noorfarooqy\EnterpriseServiceBus\Traits\HelperTrait;

class CustomerServices extends DefaultService
{
    use EsbTunnelTrait;
    use HelperTrait;


    public function createAccounts($cif_account, $override_currency = false)
    {
        $customer_cif = $cif_account->customer_id;
        if (substr($customer_cif, 0, 2) != '00') {
            $customer_cif = '00' . $customer_cif;
        }

        $payload = [
            'Sttms-Customer-IO' => [
                'CUSTNO' => $customer_cif,
            ],
        ];

        $coreBankingServices = new CoreBankingServices();

        $accounts = $coreBankingServices->QueryCustomerAccounts($payload);
        if (!$accounts) {
            return false;
        }

        return $this->extractAndSaveAccounts($accounts, $cif_account, $coreBankingServices, $override_currency);
    }

    public function extractAndSaveAccounts($accounts, $cif_account, $cbsServices, $override_currency = false)
    {
        $accounts_list = $accounts?->{'Stvws-Stdaccqy'} ?? null;
        if (is_array($accounts_list)) {
            foreach ($accounts_list as $key => $acc) {
                $account_number = $acc->CUST_AC_NO;
                $branch_code = $acc->BRANCH_CODE;

                $account_details = $cbsServices->FetchCustomerDataFromCbs($account_number, $branch_code);
                if (!$account_details) {
                    return false;
                }
                if (!$override_currency) {
                    if ($acc->CCY != 'KES') {
                        continue;
                    }
                }

                $account = $this->saveAccountToDb($account_details, $cif_account);
                if (!$account) {
                    return false;
                }
            }
        } else {
            $account_number = $accounts_list->CUST_AC_NO;
            $branch_code = $accounts_list->BRANCH_CODE;

            $account_details = $cbsServices->FetchCustomerDataFromCbs($account_number, $branch_code);
            if (!$account_details) {
                return false;
            }
            $account = $this->saveAccountToDb($account_details, $cif_account);
            if (!$account) {
                return false;
            }
        }
        return $accounts;
    }

    public function saveAccountToDb($account_details, $cif_account)
    {
        $existing_account = OnboardedCustomers::where([
            ['account_number', $account_details->{'ACC'}],
        ])->get()->first();
        if (!$existing_account) {
            $data = [
                'account_number' => $account_details->{'ACC'},
                'customer_cif_id' => $cif_account->id,
                'customer_number' => $account_details->{'CUSTNO'},
                'currency' => $account_details->{'CCY'},
                'branch_code' => $account_details->{'BRN'},
                'customer_name' => $account_details->{'CUSTNAME'},
                'customer_telephone' => $cif_account->mobile_number,
                'customer_email' => $cif_account->email,
                'adesc' => $account_details->{'ADESC'},
                'allow_debit' => $account_details->{'ACSTATNODR'} == 'Y',
                'allow_credit' => $account_details->{'ACSTATNOCR'} == 'Y',
                'account_type' => $account_details->{'ACCTYPE'},
                'is_frozen' => strcmp($account_details->{'FROZEN'}, "Y") == 0,
                'address_1' => $account_details?->{'ADDRESS_1'},
                'address_2' => $account_details?->{'ADDRESS_2'} ?? '',
                'address_3' => $account_details?->{'ADDRESS_3'} ?? '',
                'address_4' => $account_details?->{'ADDRESS_4'} ?? '',
                'account_status' => $account_details->{'ACCSTAT'},
                'maker' => $cif_account->maker,
            ];
            try {
                $account = OnboardedCustomers::create($data);
                return $account;
            } catch (\Throwable $th) {
                $this->setError(env('APP_DEBUG') ? $th->getMessage() : UssdErrorCodes::ussd_e_000->value, UssdErrorCodes::ussd_e_000, [UssdErrorCodes::ussd_e_000]);
                return $this->is_json ? $this->getResponse() : false;
            }
        }
        return $existing_account;
    }
}
