<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tembo Account ID for (Banking & Wallets, Collect Money, Make Payment) API's
    |--------------------------------------------------------------------------
    |
    | This value is the account ID of your application as provided by Tembo.
    |
    */
    'accountId' => env('TEMBO_ACCOUNT_ID', 'Tembo'),

    /*
    |--------------------------------------------------------------------------
    | Tembo Secret Key for (Banking & Wallets, Collect Money, Make Payment) API's
    |--------------------------------------------------------------------------
    |
    | This value is the secret key of your application as provided by Tembo.
    |
    */
    'secretKey' => env('TEMBO_SECRET_KEY', 'Tembo'),

    /*
    |--------------------------------------------------------------------------
    | Tembo Token for (Merchant Virtual Accounts, eKYC Services, Remittance Services) API's
    |--------------------------------------------------------------------------
    |
    | This value is the Bearer token of your application as provided by Tembo.
    |
    */
    'token' => env('TEMBO_TOKEN', 'Bearer Tembo Token'),

    /*
    |--------------------------------------------------------------------------
    | Tembo Environment
    |--------------------------------------------------------------------------
    |
    | This value is the environment of your application as registered on Tembo.
    |
    */
    'environment' => env('TEMBO_ENVIRONMENT', 'sandbox'),
];
