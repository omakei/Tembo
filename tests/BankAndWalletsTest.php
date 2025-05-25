<?php

use Illuminate\Support\Facades\Http;
use Omakei\Tembo\Exceptions\BadGatewayException;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\NotFoundException;
use Omakei\Tembo\Exceptions\UnauthorizedException;
use Omakei\Tembo\Tembo;

beforeEach(function () {
    config(['tembo.accountId' => '123456789']);
    config(['tembo.secretKey' => '123456789']);

});

it('can successful create wallet', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/create_wallet_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->createWallet([
        'firstName' => 'Manase',
        'middleName' => 'James',
        'lastName' => 'Mwina',
        'dateOfBirth' => '1985-01-01',
        'gender' => 'M',
        'identityInfo' => [
            'idType' => 'NATIONAL_ID',
            'idNumber' => '1985-1828JD1002',
            'issueDate' => '2020-01-01',
            'expiryDate' => '2028-01-01',
        ],
        'address' => [
            'street' => 'A.H Mwinyi Road',
            'city' => 'Dar es Salaam',
            'postalCode' => '255',
        ],
        'mobileNo' => '1230008914292',
        'email' => 'john@email.com',
        'currencyCode' => 'TZS',
        'externalCustomerRef' => '8773663',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate create wallet', function (array $data, string $validation) {

    $tembo = new Tembo;

    expect(fn () => $tembo->createWallet($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The first name field is required.","The middle name field is required.","The last name field is required.","The date of birth field is required.","The gender field is required.","The identity info.id type field is required.","The identity info.id number field is required.","The identity info.issue date field is required.","The identity info.expiry date field is required.","The address.street field is required.","The address.city field is required.","The address.postal code field is required.","The mobile no field is required.","The email field is required.","The currency code field is required.","The external customer ref field is required."]'],

]);

it('can throw error during create wallet', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->createWallet([
        'firstName' => 'Manase',
        'middleName' => 'James',
        'lastName' => 'Mwina',
        'dateOfBirth' => '1985-01-01',
        'gender' => 'M',
        'identityInfo' => [
            'idType' => 'NATIONAL_ID',
            'idNumber' => '1985-1828JD1002',
            'issueDate' => '2020-01-01',
            'expiryDate' => '2028-01-01',
        ],
        'address' => [
            'street' => 'A.H Mwinyi Road',
            'city' => 'Dar es Salaam',
            'postalCode' => '255',
        ],
        'mobileNo' => '1230008914292',
        'email' => 'john@email.com',
        'currencyCode' => 'TZS',
        'externalCustomerRef' => '8773663',
    ]))->toThrow($exceptionClass);

})->with([['create_wallet_400.json', BadRequestException::class, 400],
    ['create_wallet_401.json', UnauthorizedException::class, 401],
    ['create_wallet_502.txt', BadGatewayException::class, 502],
]);

it('can successful deposit funds', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/deposit_funds_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/transaction/deposit' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/transaction/deposit' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->depositFunds([
        'amount' => 1000,
        'accountNo' => '70089773',
        'externalRefNo' => 'TEMBO-CUSTOMER-003',
        'narration' => 'Customer refund for transaction TNX-499483',
        'transactionDate' => '2022-11-30',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate deposit funds', function (array $data, string $validation) {

    $tembo = new Tembo;

    expect(fn () => $tembo->depositFunds($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The amount field is required.","The account no field is required.","The external ref no field is required.","The narration field is required.","The transaction date field is required."]'],

]);

it('can throw error during deposit funds', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/transaction/deposit' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/transaction/deposit' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->depositFunds([
        'amount' => 1000,
        'accountNo' => '70089773',
        'externalRefNo' => 'TEMBO-CUSTOMER-003',
        'narration' => 'Customer refund for transaction TNX-499483',
        'transactionDate' => '2022-11-30',
    ]))->toThrow($exceptionClass);

})->with([['deposit_funds_400.json', BadRequestException::class, 400],
    ['deposit_funds_401.json', UnauthorizedException::class, 401],
]);

it('can successful withdraw funds', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/withdraw_funds_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/transaction/withdraw' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/transaction/withdraw' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->withdrawFunds([
        'amount' => 1000,
        'accountNo' => '70089773',
        'externalRefNo' => 'TEMBO-CUSTOMER-003',
        'narration' => 'Customer refund for transaction TNX-499483',
        'transactionDate' => '2022-11-30',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate withdraw funds', function (array $data, string $validation) {

    $tembo = new Tembo;

    expect(fn () => $tembo->withdrawFunds($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The amount field is required.","The account no field is required.","The external ref no field is required.","The narration field is required.","The transaction date field is required."]'],

]);

it('can throw error during withdraw funds', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/transaction/withdraw' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/transaction/withdraw' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->withdrawFunds([
        'amount' => 1000,
        'accountNo' => '70089773',
        'externalRefNo' => 'TEMBO-CUSTOMER-003',
        'narration' => 'Customer refund for transaction TNX-499483',
        'transactionDate' => '2022-11-30',
    ]))->toThrow($exceptionClass);

})->with([['withdraw_funds_400.json', BadRequestException::class, 400],
    ['withdraw_funds_401.json', UnauthorizedException::class, 401],
]);

it('can successful do wallet to wallet transfer', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/wallet_to_wallet_transfer_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/transaction/transfer' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/transaction/transfer' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->walletTransfer([
        'amount' => 10000,
        'fromAccountNo' => '70089773',
        'toAccountNo' => '70089004',
        'externalRefNo' => 'TEMBO-CUSTOMER-004',
        'narration' => 'Integration Test...',
        'transactionDate' => '2022-11-30',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate wallet to wallet transfer', function (array $data, string $validation) {

    $tembo = new Tembo;

    expect(fn () => $tembo->walletTransfer($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The amount field is required.","The from account no field is required.","The to account no field is required.","The external ref no field is required.","The narration field is required.","The transaction date field is required."]'],

]);

it('can throw error during wallet to wallet transfer', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/transaction/transfer' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/transaction/transfer' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->walletTransfer([
        'amount' => 10000,
        'fromAccountNo' => '70089773',
        'toAccountNo' => '70089004',
        'externalRefNo' => 'TEMBO-CUSTOMER-004',
        'narration' => 'Integration Test...',
        'transactionDate' => '2022-11-30',
    ]))->toThrow($exceptionClass);

})->with([['wallet_to_wallet_transfer_400.json', BadRequestException::class, 400],
    ['wallet_to_wallet_transfer_401.json', UnauthorizedException::class, 401],
]);

it('can successful check wallet balance', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/wallet_balance_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/balance' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/balance' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->walletBalance([
        'accountNo' => '70089773',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate check wallet balance', function (array $data, string $validation) {

    $tembo = new Tembo;

    expect(fn () => $tembo->walletBalance($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The account no field is required."]'],

]);

it('can throw error during check wallet balance', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/balance' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/balance' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->walletBalance([
        'accountNo' => '70089773',
    ]))->toThrow($exceptionClass);

})->with([['wallet_balance_404.json', NotFoundException::class, 404],
    ['wallet_balance_400.json', BadRequestException::class, 400],
]);

it('can successful get wallet statement', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/get_wallet_statement_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/statement' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/statement' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->walletStatement([
        'accountNo' => '015A8XX787600',
        'startDate' => '2024-12-12',
        'endDate' => '2024-12-13',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate get wallet statement', function (array $data, string $validation) {
    $tembo = new Tembo;

    expect(fn () => $tembo->walletStatement($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The account no field is required.","The start date field is required.","The end date field is required."]'],
    ['data' => ['accountNo' => '015A8XX787600'], 'validation' => '["The start date field is required.","The end date field is required."]'],
    ['data' => ['startDate' => '2024-12-12', 'endDate' => '2024-12-13'], 'validation' => '["The account no field is required."]'],
    ['data' => ['accountNo' => '015A8XX787600', 'startDate' => '2024-12-12'], 'validation' => '["The end date field is required."]'],
]);

it('can throw error during get wallet statement', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/statement' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/statement' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->walletStatement([
        'accountNo' => '015A8XX787600',
        'startDate' => '2024-12-12',
        'endDate' => '2024-12-13',
    ]))->toThrow($exceptionClass);

})->with([['get_wallet_statement_400.json', BadRequestException::class, 400],
    ['get_wallet_statement_404.json', NotFoundException::class, 404],
    ['get_wallet_statement_401.json', UnauthorizedException::class, 401],
]);

it('can successful check wallet main balance', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/wallet_main_balance_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/main-balance' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/main-balance' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->mainBalance();

    $this->assertEquals($data, $stub);
});

it('can throw error during check wallet main balance', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/balance' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/balance' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->mainBalance())->toThrow($exceptionClass);

})->with([
    ['wallet_main_balance_401.json', UnauthorizedException::class, 401],
]);

it('can successful get wallet main statement', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/get_wallet_main_statement_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/main-statement' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/main-statement' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->mainStatement([
        'startDate' => '2024-12-12',
        'endDate' => '2024-12-13',
    ]);

    $this->assertEquals($data, $stub);
});

it('can successful validate get wallet main statement', function (array $data, string $validation) {
    $tembo = new Tembo;

    expect(fn () => $tembo->mainStatement($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The start date field is required.","The end date field is required."]'],
]);

it('can throw error during get wallet main statement', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/main-statement' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/main-statement' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->mainStatement([
        'startDate' => '2024-12-12',
        'endDate' => '2024-12-13',
    ]))->toThrow($exceptionClass);

})->with([['get_wallet_main_statement_400.json', BadRequestException::class, 400],
    ['get_wallet_main_statement_401.json', UnauthorizedException::class, 401],
]);

it('can successful get list of wallets', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/get_wallets_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet' => Http::response($stub, 200),
    ]);

    $tembo = new Tembo;

    $data = $tembo->listWallets();

    $this->assertEquals($data, $stub);
});

it('can throw error during get list of wallets', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/BankAndWallet/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet' => Http::response($stub, $statusCode),
    ]);

    $tembo = new Tembo;

    expect(fn () => $tembo->listWallets())->toThrow($exceptionClass);

})->with([
    ['get_wallets_401.json', UnauthorizedException::class, 401],
]);
