<?php

namespace Omakei\Tembo\Traits\Validation;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait EKYCValidation
{
    /**
     * @param  array<string, string>  $payload
     *
     * @throws Exception
     */
    private function validateInitiateOnboardRequestInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'nin' => ['required', 'string'],
            'phoneNumber' => ['required', 'string'],
            'email' => ['required', 'email'],
            'cardIssueDate' => ['required', 'date', Rule::date()->format('Y-m-d')],
            'cardExpiryDate' => ['required', 'date', Rule::date()->format('Y-m-d')],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateReplyToAQuestionInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'onboardId' => ['required', 'string'],
            'questionCode' => ['required', 'string'],
            'answer' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

    private function validateRetrieveFirstQuestionInput(array $payload)
    {
        $validator = Validator::make($payload, [
            'onboardId' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors()->all());
            throw new Exception($errors);
        }
    }

}
