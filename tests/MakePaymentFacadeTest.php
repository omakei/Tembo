<?php

use Illuminate\Support\Facades\Http;
use Omakei\Tembo\Exceptions\UnauthorizedException;
use Omakei\Tembo\Facades\Tembo as TemboFacade;
use Omakei\Tembo\Tembo;

beforeEach(function () {
    config(['tembo.accountId' => '123456789']);
    config(['tembo.secretKey' => '123456789']);

});

it('can successful transfer funds from wallet to mobile', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/MakePayment/wallet_to_mobile_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/payment/wallet-to-mobile' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/payment/wallet-to-mobile' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::walletToMobile([
        'countryCode' => 'TZ',
        'accountNo' => '70089773',
        'serviceCode' => 'TZ-TIGO-B2C',
        'amount' => 1000,
        'msisdn' => '255713809050',
        'narration' => 'MOBILE PAYOUT',
        'currencyCode' => 'TZS',
        'recipientNames' => 'Alex Kaiza',
        'transactionRef' => '20f807fe-3ee8-4525-8aff-ccb95de38250',
        'transactionDate' => '2023-02-16 17:00:01',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate  transfer funds from wallet to mobile', function (array $data, string $validation) {

    expect(fn () => TemboFacade::walletToMobile($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The country code field is required.","The account no field is required.","The service code field is required.","The amount field is required.","The msisdn field is required.","The narration field is required.","The currency code field is required.","The recipient names field is required.","The transaction ref field is required.","The transaction date field is required."]'],
])->group('facade');

it('can throw error during  transfer funds from wallet to mobile', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/MakePayment/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/payment/wallet-to-mobile' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/payment/wallet-to-mobile' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::walletToMobile([
        'countryCode' => 'TZ',
        'accountNo' => '70089773',
        'serviceCode' => 'TZ-TIGO-B2C',
        'amount' => 1000,
        'msisdn' => '255713809050',
        'narration' => 'MOBILE PAYOUT',
        'currencyCode' => 'TZS',
        'recipientNames' => 'Alex Kaiza',
        'transactionRef' => '20f807fe-3ee8-4525-8aff-ccb95de38250',
        'transactionDate' => '2023-02-16 17:00:01',
    ]))->toThrow($exceptionClass);

})->with([['wallet_to_mobile_401.json', UnauthorizedException::class, 401],
])->group('facade');

it('can successful transfer funds from wallet to bank', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/MakePayment/wallet_to_bank_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/payment/wallet-to-mobile' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/payment/wallet-to-mobile' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::walletToMobile([
        'countryCode' => 'TZ',
        'accountNo' => '70089773',
        'serviceCode' => 'TZ-TIGO-B2C',
        'amount' => 1000,
        'msisdn' => '255713809050',
        'narration' => 'MOBILE PAYOUT',
        'currencyCode' => 'TZS',
        'recipientNames' => 'Alex Kaiza',
        'transactionRef' => '20f807fe-3ee8-4525-8aff-ccb95de38250',
        'transactionDate' => '2023-02-16 17:00:01',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate  transfer funds from wallet to bank', function (array $data, string $validation) {

    expect(fn () => TemboFacade::walletToMobile($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The country code field is required.","The account no field is required.","The service code field is required.","The amount field is required.","The msisdn field is required.","The narration field is required.","The currency code field is required.","The recipient names field is required.","The transaction ref field is required.","The transaction date field is required."]'],
])->group('facade');

it('can throw error during  transfer funds from wallet to bank', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/MakePayment/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/payment/wallet-to-mobile' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/payment/wallet-to-mobile' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::walletToMobile([
        'countryCode' => 'TZ',
        'accountNo' => '70089773',
        'serviceCode' => 'TZ-TIGO-B2C',
        'amount' => 1000,
        'msisdn' => '255713809050',
        'narration' => 'MOBILE PAYOUT',
        'currencyCode' => 'TZS',
        'recipientNames' => 'Alex Kaiza',
        'transactionRef' => '20f807fe-3ee8-4525-8aff-ccb95de38250',
        'transactionDate' => '2023-02-16 17:00:01',
    ]))->toThrow($exceptionClass);

})->with([['wallet_to_bank_401.json', UnauthorizedException::class, 401],
])->group('facade');

it('can successful do utility payments', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/MakePayment/utility_payment_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/payment/biller' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/payment/biller' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::utilityPayments([
        'countryCode' => 'TZ',
        'accountNo' => '8800175745',
        'serviceCode' => 'TZ-BILLER',
        'amount' => 1200,
        'msisdn' => '255717898844',
        'narration' => 'LUKU TEST PAYMENT',
        'currencyCode' => 'TZS',
        'recipientNames' => 'ALEX JOHN KIRIA',
        'transactionRef' => 'YHSE78473DN',
        'transactionDate' => '2024-08-12 16:00:01',
        'meta' => [
            'billerCode' => 'LUKU',
            'billerReference' => '458574736222',
        ],
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate utility payments', function (array $data, string $validation) {

    expect(fn () => TemboFacade::utilityPayments($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The country code field is required.","The account no field is required.","The service code field is required.","The amount field is required.","The msisdn field is required.","The narration field is required.","The currency code field is required.","The recipient names field is required.","The transaction ref field is required.","The transaction date field is required.","The meta.biller code field is required.","The meta.biller reference field is required."]'],
])->group('facade');

it('can throw error during utility payments', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/MakePayment/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/payment/biller' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/payment/biller' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::utilityPayments([
        'countryCode' => 'TZ',
        'accountNo' => '8800175745',
        'serviceCode' => 'TZ-BILLER',
        'amount' => 1200,
        'msisdn' => '255717898844',
        'narration' => 'LUKU TEST PAYMENT',
        'currencyCode' => 'TZS',
        'recipientNames' => 'ALEX JOHN KIRIA',
        'transactionRef' => 'YHSE78473DN',
        'transactionDate' => '2024-08-12 16:00:01',
        'meta' => [
            'billerCode' => 'LUKU',
            'billerReference' => '458574736222',
        ],
    ]))->toThrow($exceptionClass);

})->with([['utility_payment_401.json', UnauthorizedException::class, 401],
])->group('facade');

it('can successful check utility payments status', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/MakePayment/payment_status_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/payment/status' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/payment/status' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::utilityPaymentStatus([
        'transactionRef' => '20f807fe-3ee8-4525-8aff-ccb95de38250',
        'transactionId' => 'X50jcLD-U',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate check utility payments status', function (array $data, string $validation) {

    expect(fn () => TemboFacade::utilityPaymentStatus($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The transaction id field is required.","The transaction ref field is required."]'],
])->group('facade');

it('can throw error during check utility payments status', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/MakePayment/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/payment/status' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/payment/status' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::utilityPaymentStatus([
        'transactionRef' => '20f807fe-3ee8-4525-8aff-ccb95de38250',
        'transactionId' => 'X50jcLD-U',
    ]))->toThrow($exceptionClass);

})->with([['payment_status_401.json', UnauthorizedException::class, 401],
])->group('facade');
