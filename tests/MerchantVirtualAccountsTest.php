<?php

use Illuminate\Support\Facades\Http;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\ConflictException;
use Omakei\Tembo\Exceptions\NotFoundException;
use Omakei\Tembo\Tembo;

beforeEach(function () {
    config(['tembo.accountId' => '123456789']);
    config(['tembo.secretKey' => '123456789']);

});

it('can successful create merchant virtual account', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/VirtualMerchantAccount/create_merchant_virtual_account_201.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/account' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/account' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->createMerchantVirtualAccount([
        'companyName' => 'TEMBOPLUS COMPANY LIMITED',
        'reference' => 'VT87038HZS',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate create merchant virtual account', function (array $data, string $validation) {

    $tembo = new Tembo;

    expect(fn () => $tembo->createMerchantVirtualAccount($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The company name field is required.","The reference field is required."]'],
    ['data' => ['companyName' => 'TEMBOPLUS COMPANY LIMITED'], 'validation' => '["The reference field is required."]'],
    ['data' => ['reference' => 'VT87038HZS'], 'validation' => '["The company name field is required."]'],
]);

it('can throw error during create merchant virtual account', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/VirtualMerchantAccount/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/account' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/account' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->createMerchantVirtualAccount([
        'companyName' => 'TEMBOPLUS COMPANY LIMITED',
        'reference' => 'VT87038HZS',
    ]))->toThrow($exceptionClass);

})->with([['create_merchant_virtual_account_400.json', BadRequestException::class, 400],
    ['create_merchant_virtual_account_409.json', ConflictException::class, 409],
]);

it('can successful get merchant account balance', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/VirtualMerchantAccount/get_account_balance_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/account/balance' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/account/balance' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->getAccountBalance([
        'accountNo' => '015A8XX787600',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate get merchant account balance', function (array $data, string $validation) {
    $tembo = new Tembo;

    expect(fn () => $tembo->getAccountBalance($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The account no field is required."]'],
]);

it('can throw error during get merchant account balance', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/VirtualMerchantAccount/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/account/balance' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/account/balance' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->getAccountBalance([
        'accountNo' => '015A8XX787600',
    ]))->toThrow($exceptionClass);

})->with([['get_account_balance_400.json', BadRequestException::class, 400],
    ['get_account_balance_404.txt', NotFoundException::class, 404],
]);

it('can successful get merchant account statement', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/VirtualMerchantAccount/get_account_statement_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/account/statement' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/account/statement' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->getAccountStatement([
        'accountNo' => '015A8XX787600',
        'startDate' => '2024-12-12',
        'endDate' => '2024-12-13',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate get merchant account statement', function (array $data, string $validation) {
    $tembo = new Tembo;

    expect(fn () => $tembo->getAccountStatement($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The account no field is required.","The start date field is required.","The end date field is required."]'],
    ['data' => ['accountNo' => '015A8XX787600'], 'validation' => '["The start date field is required.","The end date field is required."]'],
    ['data' => ['startDate' => '2024-12-12', 'endDate' => '2024-12-13'], 'validation' => '["The account no field is required."]'],
    ['data' => ['accountNo' => '015A8XX787600', 'startDate' => '2024-12-12'], 'validation' => '["The end date field is required."]'],
]);

it('can throw error during get merchant account statement', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/VirtualMerchantAccount/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/account/statement' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/account/statement' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->getAccountStatement([
        'accountNo' => '015A8XX787600',
        'startDate' => '2024-12-12',
        'endDate' => '2024-12-13',
    ]))->toThrow($exceptionClass);

})->with([['get_account_statement_400.json', BadRequestException::class, 400],
    ['get_account_statement_404.txt', NotFoundException::class, 404],
]);
