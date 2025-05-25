<?php

namespace Omakei\Tembo\Traits\Validation;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait CollectMoneyValidation
{
    /**
     * @param  array<string, string>  $payload
     *
     * @throws Exception
     */
    private function validateSendAUSSDPushRequestInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'channel' => ['required', 'string', Rule::in(['TZ-TIGO-C2B', 'TZ-AIRTEL-C2B'])],
            'amount' => ['required', 'numeric'],
            'msisdn' => ['required', 'string'],
            'narration' => ['required', 'string'],
            'transactionRef' => ['required', 'string'],
            'transactionDate' => ['required', 'date', Rule::date()->format('Y-m-d H:i:s')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateCollectionStatementInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'startDate' => ['required', 'date', 'before:endDate', Rule::date()->format('Y-m-d')],
            'endDate' => ['required', 'date', 'after:startDate', Rule::date()->format('Y-m-d')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateCollectionPaymentStatusInput(array $payload)
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
