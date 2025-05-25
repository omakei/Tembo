<?php

namespace Omakei\Tembo\Traits\Validation;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait BankingAndWalletsValidation
{
    /**
     * @param  array<string, string>  $payload
     *
     * @throws Exception
     */
    private function validateCreateWalletInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'firstName' => ['required', 'string'],
            'middleName' => ['required', 'string'],
            'lastName' => ['required', 'string'],
            'dateOfBirth' => ['required', 'string', 'date', Rule::date()->format('Y-m-d')],
            'gender' => ['required', 'string', Rule::in(['M', 'F'])],
            'identityInfo.idType' => ['required', 'string', Rule::in(['DRIVER_LICENSE', 'VOTER_ID', 'INTL_PASSPORT', 'NATIONAL_ID'])],
            'identityInfo.idNumber' => ['required', 'string'],
            'identityInfo.issueDate' => ['required', 'date', 'before:expiryDate', Rule::date()->format('Y-m-d')],
            'identityInfo.expiryDate' => ['required', 'date', 'before:issueDate', Rule::date()->format('Y-m-d')],
            'address.street' => ['required', 'string'],
            'address.city' => ['required', 'string'],
            'address.postalCode' => ['required', 'string'],
            'mobileNo' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'currencyCode' => ['required', 'string', Rule::in(['TZS', 'KES', 'UGS', 'USD'])],
            'externalCustomerRef' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateDepositFundsInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'amount' => ['required', 'numeric'],
            'accountNo' => ['required', 'string'],
            'externalRefNo' => ['required', 'string'],
            'narration' => ['required', 'string'],
            'transactionDate' => ['required', 'date', Rule::date()->format('Y-m-d')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateWithdrawFundsInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'amount' => ['required', 'numeric'],
            'accountNo' => ['required', 'string'],
            'externalRef' => ['required', 'string'],
            'narration' => ['required', 'string'],
            'transactionDate' => ['required', 'date', Rule::date()->format('Y-m-d')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateWalletTransferInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'amount' => ['required', 'numeric'],
            'fromAccountNo' => ['required', 'string'],
            'toAccountNo' => ['required', 'string'],
            'externalRefNo' => ['required', 'string'],
            'narration' => ['required', 'string'],
            'transactionDate' => ['required', 'date', Rule::date()->format('Y-m-d')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateWalletBalanceInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'accountNo' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateWalletStatementInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'accountNo' => ['required', 'string'],
            'startDate' => ['required', 'date', Rule::date()->format('Y-m-d')],
            'endDate' => ['required', 'date', Rule::date()->format('Y-m-d')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateMainStatementInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'startDate' => ['required', 'date', Rule::date()->format('Y-m-d')],
            'endDate' => ['required', 'date', Rule::date()->format('Y-m-d')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }
}
