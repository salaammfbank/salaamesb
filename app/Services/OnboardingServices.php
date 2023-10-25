<?php

namespace Noorfarooqy\SalaamEsb\Services;

use Noorfarooqy\LaravelOnfon\Services\DefaultService;
use Noorfarooqy\SalaamEsb\Contracts\OnboardingErrorCodes;

class OnboardingServices extends DefaultService
{

    public function OnboardCustomer($request)
    {
        $this->request = $request;
        $this->setResponseType();

        $this->rules = [
            'customer_cif' => 'required|string|max:25',
            'telephone_number' => 'required|string|max:14|min:9',
            'email_address' => 'required|email',
        ];
        $this->customValidate();
        if ($this->has_failed) {
            $this->setError($this->getMessage(), OnboardingErrorCodes::ussd_e_005->name, [OnboardingErrorCodes::ussd_e_005]);
            return $this->is_json ? $this->getResponse() : false;
        }

        if ($this->has_failed) {
            $this->setError($this->getMessage(), OnboardingErrorCodes::ussd_e_003->name, [OnboardingErrorCodes::ussd_e_003]);
            return $this->is_json ? $this->getResponse() : false;
        }
        $data = $this->validatedData();

        $already_on_boarded = CustomerCifs::where([
            ['customer_id', $data['customer_cif']],
        ])->orWhere('mobile_number', $data['telephone_number'])
            ->orWhere('preferred_mobile_number', $data['telephone_number'])->exists();

        if ($already_on_boarded) {
            $this->setError("Existing customer cif or mobile number. Cannot onboard customer");
            return $this->is_json ? $this->getResponse() : false;
        }

        $cbsServices = new CbsServices();

        $customer_cif_request = [
            'Stvws-Stdcifqy-Query-IO' => [
                'KEY_ID' => 'C',
                'VALUE' => $data['customer_cif'],
            ],
        ];

        $customer_cif = $cbsServices->GetCustomerByCifDetail($customer_cif_request);
        if (!$customer_cif) {
            $this->setError($cbsServices->getMessage(), OnboardingErrorCodes::ussd_c_004->name, [OnboardingErrorCodes::ussd_c_004]);
            // $this->setStatus(500);
            return $this->is_json ? $this->getResponse() : false;
        }

        $data = [
            'customer_id' => $customer_cif->{'Stvws-Stdcifqy'}?->{'CUSTOMER_ID'},
            'email' => $customer_cif->{'Stvws-Stdcifqy'}?->{'EMAIL'} ?? '',
            'preferred_email' => $data['email_address'],
            'first_name' => $customer_cif->{'Stvws-Stdcifqy'}?->{'FIRST_NAME'} ?? $customer_cif->{'Stvws-Stdcifqy'}?->{'SHORT_NAME'},
            'last_name' => $customer_cif->{'Stvws-Stdcifqy'}?->{'LAST_NAME'} ?? '',
            'gender' => $customer_cif->{'Stvws-Stdcifqy'}?->{'GENDER'} ?? 'Unknown',
            'title' => $customer_cif->{'Stvws-Stdcifqy'}?->{'TITLE'} ?? '',
            'short_name' => $customer_cif->{'Stvws-Stdcifqy'}?->{'SHORT_NAME'},
            'address_line1' => $customer_cif->{'Stvws-Stdcifqy'}?->{'ADDRESS_LINE1'} ?? '',
            'address_line2' => $customer_cif->{'Stvws-Stdcifqy'}?->{'ADDRESS_LINE2'} ?? '',
            'address_line3' => $customer_cif->{'Stvws-Stdcifqy'}?->{'ADDRESS_LINE3'} ?? '',
            'address_line4' => $customer_cif->{'Stvws-Stdcifqy'}?->{'ADDRESS_LINE4'} ?? '',
            'address_country' => $customer_cif->{'Stvws-Stdcifqy'}?->{'ADDRESS_COUNTRY'},
            'preferred_mobile_number' => $data['telephone_number'],
            'mobile_number' => $customer_cif->{'Stvws-Stdcifqy'}?->{'MOBILE_NO'},
            'is_verified' => $customer_cif->{'Stvws-Stdcifqy'}?->{'IS_VERIFIED'} ?? false,
            'nationality' => $customer_cif->{'Stvws-Stdcifqy'}?->{'NAITONALITY'} ?? 'Kenya',
            'unique_id_name' => $customer_cif->{'Stvws-Stdcifqy'}?->{'UNIQUE_ID_NAME'} ?? 'NONE',
            'unique_id_value' => $customer_cif->{'Stvws-Stdcifqy'}?->{'UNIQUE_ID_VALUE'} ?? 'UNSET',
            'cif_created_at' => $customer_cif->{'Stvws-Stdcifqy'}?->{'CREATED_AT'},
            'customer_type' => $customer_cif->{'Stvws-Stdcifqy'}?->{'CUSTOMER_TYPE'},
            'category' => $customer_cif->{'Stvws-Stdcifqy'}?->{'CATEGORY'},
            'maker' => $request?->user()->id,
        ];

        try {
            DB::beginTransaction();
            $cif_account = CustomerCifs::create($data);
            $accounts_created = $this->createAccounts($cif_account, $cbsServices);
            if (!$accounts_created) {
                $this->is_json ? $this->getResponse() : false;
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setError(env('APP_DEBUG') ? $th->getMessage() : OnboardingErrorCodes::ussd_e_000->value, OnboardingErrorCodes::ussd_e_000, [OnboardingErrorCodes::ussd_e_000]);
            return $this->is_json ? $this->getResponse() : false;
        }

        $this->setSuccess('success');
        return $this->is_json ? $this->getResponse($data) : $data;
    }
}
