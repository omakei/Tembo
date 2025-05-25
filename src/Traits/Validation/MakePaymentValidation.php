<?php

namespace Omakei\Tembo\Traits\Validation;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait MakePaymentValidation
{
    /**
     * @param  array<string, string>  $payload
     *
     * @throws Exception
     */
    private function validateWalletToMobileInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'countryCode' => ['required', 'string', Rule::in(['TZ'])],
            'accountNo' => ['required', 'string'],
            'serviceCode' => ['required', 'string', Rule::in(['TZ-TIGO-B2C', 'TZ-AIRTEL-B2C', 'TZ-BANK-B2C', 'TZ-BILLER'])],
            'amount' => ['required', 'numeric'],
            'msisdn' => ['required', 'string'],
            'narration' => ['required', 'string'],
            'currencyCode' => ['required', 'string', Rule::in(['TZS'])],
            'recipientNames' => ['required', 'string'],
            'transactionRef' => ['required', 'string'],
            'transactionDate' => ['required', 'date', Rule::date()->format('Y-m-d H:i:s')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    /**
     * @param  array<string, string>  $payload
     *
     * @throws Exception
     */
    private function validateUtilityPaymentsInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'countryCode' => ['required', 'string', Rule::in(['TZ'])],
            'accountNo' => ['required', 'string'],
            'serviceCode' => ['required', 'string', Rule::in(['TZ-BILLER'])],
            'amount' => ['required', 'numeric'],
            'msisdn' => ['required', 'string'],
            'narration' => ['required', 'string'],
            'currencyCode' => ['required', 'string', Rule::in(['TZS'])],
            'recipientNames' => ['required', 'string'],
            'transactionRef' => ['required', 'string'],
            'transactionDate' => ['required', 'date', Rule::date()->format('Y-m-d H:i:s')],
            'meta.billerCode' => ['required', 'string'],
            'meta.billerReference' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validatePaymentStatusInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'transactionId' => ['required', 'string'],
            'transactionRef' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }
}
