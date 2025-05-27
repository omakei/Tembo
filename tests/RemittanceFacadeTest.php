<?php

use Illuminate\Support\Facades\Http;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\ForbiddenException;
use Omakei\Tembo\Exceptions\RateLimitException;
use Omakei\Tembo\Facades\Tembo as TemboFacade;
use Omakei\Tembo\Tembo;

beforeEach(function () {
    config(['tembo.accountId' => '123456789']);
    config(['tembo.secretKey' => '123456789']);

});

it('can successful create remittance', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/Remittance/remittance_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/remittance' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/remittance' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::createRemittance([
        'paymentDate' => '2025-02-27T10:56:00Z',
        'senderCurrency' => 'USD',
        'senderAmount' => 100.00,
        'receiverCurrency' => 'TZS',
        'receiverAmount' => 250000.00,
        'exchangeRate' => 2500.00,
        'receiverAccount' => '255745908755',
        'receiverChannel' => 'MOBILE',
        'institutionCode' => 'VODACOM',
        'partnerReference' => 'HSC8474837-VS83',
        'sender' => [
            'fullName' => 'JOHN DOE',
            'nationality' => 'US',
            'countryCode' => 'US',
            'idType' => 'PASSPORT',
            'idNumber' => 'A12345678',
            'idExpiryDate' => '2027-08-30',
            'dateOfBirth' => '2002-09-12',
            'phoneNumber' => '1234567890',
            'email' => 'johndoe@example.com',
            'address' => '123 Main Street, New York, USA',
            'sourceOfFundsDeclaration' => 'Salary',
            'purposeOfTransaction' => 'Home Support',
            'occupation' => 'Software Engineer',
            'employer' => 'Tech Corp Ltd',
        ],
        'receiver' => [
            'fullName' => 'AMINA ABBDALLAH HASSAN',
            'phoneNumber' => '255712345678',
            'email' => null,
            'countryCode' => 'TZ',
        ],
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate create remittance', function (array $data, string $validation) {

    expect(fn () => TemboFacade::createRemittance($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The payment date field is required.","The sender currency field is required.","The sender amount field is required.","The receiver currency field is required.","The receiver amount field is required.","The exchange rate field is required.","The receiver account field is required.","The receiver channel field is required.","The institution code field is required.","The partner reference field is required.","The sender.full name field is required.","The sender.nationality field is required.","The sender.country code field is required.","The sender.id type field is required.","The sender.id number field is required.","The sender.id expiry date field is required.","The sender.date of birth field is required.","The sender.phone number field is required.","The sender.email field is required.","The sender.address field is required.","The sender.source of funds declaration field is required.","The sender.purpose of transaction field is required.","The sender.occupation field is required.","The sender.employer field is required.","The receiver.full name field is required.","The receiver.phone number field is required.","The receiver.country code field is required."]'],
])->group('facade');

it('can throw error during create remittance', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/Remittance/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/remittance' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/remittance' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::createRemittance([
        'paymentDate' => '2025-02-27T10:56:00Z',
        'senderCurrency' => 'USD',
        'senderAmount' => 100.00,
        'receiverCurrency' => 'TZS',
        'receiverAmount' => 250000.00,
        'exchangeRate' => 2500.00,
        'receiverAccount' => '255745908755',
        'receiverChannel' => 'MOBILE',
        'institutionCode' => 'VODACOM',
        'partnerReference' => 'HSC8474837-VS83',
        'sender' => [
            'fullName' => 'JOHN DOE',
            'nationality' => 'US',
            'countryCode' => 'US',
            'idType' => 'PASSPORT',
            'idNumber' => 'A12345678',
            'idExpiryDate' => '2027-08-30',
            'dateOfBirth' => '2002-09-12',
            'phoneNumber' => '1234567890',
            'email' => 'johndoe@example.com',
            'address' => '123 Main Street, New York, USA',
            'sourceOfFundsDeclaration' => 'Salary',
            'purposeOfTransaction' => 'Home Support',
            'occupation' => 'Software Engineer',
            'employer' => 'Tech Corp Ltd',
        ],
        'receiver' => [
            'fullName' => 'AMINA ABBDALLAH HASSAN',
            'phoneNumber' => '255712345678',
            'email' => null,
            'countryCode' => 'TZ',
        ],
    ]))->toThrow($exceptionClass);

})->with([['remittance_403.json', ForbiddenException::class, 403], ['remittance_400.json', BadRequestException::class, 400],
    ['remittance_429.json', RateLimitException::class, 429],
])->group('facade');

it('can successful check remittance status', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/Remittance/remittance_status_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/remittance/HSC8474837-VS83/status' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/remittance/HSC8474837-VS83/status' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::remittanceTransactionStatus([
        'partnerReference' => 'HSC8474837-VS83',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate check remittance status', function (array $data, string $validation) {

    expect(fn () => TemboFacade::remittanceTransactionStatus($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The partner reference field is required."]'],
])->group('facade');

it('can throw error during check remittance status', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/Remittance/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/remittance/HSC8474837-VS83/status' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/remittance/HSC8474837-VS83/status' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::remittanceTransactionStatus([
        'partnerReference' => 'HSC8474837-VS83',
    ]))->toThrow($exceptionClass);

})->with([['remittance_status_403.json', ForbiddenException::class, 403], ['remittance_status_400.json', BadRequestException::class, 400],
])->group('facade');
