<?php

namespace Omakei\Tembo\Traits\Validation;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait MerchantVirtualAccountsValidation
{
    /**
     * @param  array<string, string>  $payload
     *
     * @throws Exception
     */
    private function validateCreateMerchantVirtualAccountInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'companyName' => ['required', 'string'],
            'reference' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());

            throw new Exception($errors);
        }
    }

    private function validateGetAccountBalanceInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'accountNo' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateGetAccountStatementInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'accountNo' => ['required', 'string'],
            'startDate' => ['required', 'date', 'before:end_date',  Rule::date()->format('Y-m-d')],
            'endDate' => ['required', 'date', 'after:start_date',  Rule::date()->format('Y-m-d')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validatePostCheckoutInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'amount' => ['required', 'numeric'],
            'appName' => ['required', 'string'],
            'cart.items.*.name' => ['required', 'string'],
            'clientId' => ['required', 'string'],
            'currency' => ['required', 'string', Rule::in(['TZS'])],
            'externalId' => ['required', 'string', 'max:30'],
            'language' => ['required', 'string'],
            'redirectFailURL' => ['required', 'string'],
            'redirectSuccessURL' => ['required', 'string'],
            'requestOrigin' => ['required', 'string'],
            'vendorId' => ['required', 'string'],
            'vendorName' => ['required', 'string'],

        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }
}
