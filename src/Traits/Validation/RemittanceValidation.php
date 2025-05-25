<?php

namespace Omakei\Tembo\Traits\Validation;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait RemittanceValidation
{
    /**
     * @param  array<string, string>  $payload
     *
     * @throws Exception
     */
    private function validateCreateRemittanceInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'paymentDate' => ['required', 'date', Rule::date()->format('Y-m-d\TH:i:s\Z')],
            'senderCurrency' => ['required', 'string', Rule::in(['TZS', 'USD'])],
            'senderAmount' => ['required', 'numeric'],
            'receiverCurrency' => ['required', 'string', Rule::in(['TZS', 'USD'])],
            'receiverAmount' => ['required', 'numeric'],
            'exchangeRate' => ['required', 'numeric'],
            'receiverAccount' => ['required', 'string'],
            'receiverChannel' => ['required', 'string'],
            'institutionCode' => ['required', 'string'],
            'partnerReference' => ['required', 'string'],
            'sender.fullName' => ['required', 'string'],
            'sender.nationality' => ['required', 'string'],
            'sender.countryCode' => ['required', 'string'],
            'sender.idType' => ['required', 'string'],
            'sender.idNumber' => ['required', 'string'],
            'sender.idExpiryDate' => ['required', 'string'],
            'sender.dateOfBirth' => ['required', 'date', Rule::date()->format('Y-m-d')],
            'sender.phoneNumber' => ['required', 'string'],
            'sender.email' => ['required', 'email'],
            'sender.address' => ['required', 'string'],
            'sender.sourceOfFundsDeclaration' => ['required', 'string'],
            'sender.purposeOfTransaction' => ['required', 'string'],
            'sender.occupation' => ['required', 'string'],
            'sender.employer' => ['required', 'string'],
            'receiver.fullName' => ['required', 'string'],
            'receiver.phoneNumber' => ['required', 'string'],
            'receiver.email' => ['nullable', 'email'],
            'receiver.countryCode' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateRemittanceTransactionStatusInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'partnerReference' => ['required', 'string'],

        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }
}
