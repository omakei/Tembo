<?php

use Illuminate\Support\Facades\Http;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\UnauthorizedException;
use Omakei\Tembo\Facades\Tembo as TemboFacade;
use Omakei\Tembo\Tembo;

beforeEach(function () {
    config(['tembo.accountId' => '123456789']);
    config(['tembo.secretKey' => '123456789']);

});

it('can successful send ussd push request', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/CollectMoney/ussd_push_request_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/collection' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/collection' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::sendAUSSDPushRequest([
        'channel' => 'TZ-AIRTEL-C2B',
        'msisdn' => '255778342299',
        'amount' => 1000,
        'transactionRef' => 'ARC5847AF',
        'narration' => 'Inbound trx from 0713809050',
        'transactionDate' => '2023-08-22 16:15:33',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate send ussd push request', function (array $data, string $validation) {

    expect(fn () => TemboFacade::sendAUSSDPushRequest($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The channel field is required.","The amount field is required.","The msisdn field is required.","The narration field is required.","The transaction ref field is required.","The transaction date field is required."]'],
])->group('facade');

it('can throw error during send ussd push request', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/CollectMoney/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/collection' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/collection' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::sendAUSSDPushRequest([
        'channel' => 'TZ-AIRTEL-C2B',
        'msisdn' => '255778342299',
        'amount' => 1000,
        'transactionRef' => 'ARC5847AF',
        'narration' => 'Inbound trx from 0713809050',
        'transactionDate' => '2023-08-22 16:15:33',
    ]))->toThrow($exceptionClass);

})->with([['ussd_push_request_401.json', UnauthorizedException::class, 401],
])->group('facade');

it('can successful check collection balance', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/CollectMoney/collection_balance_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/collection-balance' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/collection-balance' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::collectionBalance();

    $this->assertEquals($data, $stub);
})->group('facade');

it('can throw error during check collection balance', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/CollectMoney/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/collection-balance' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/collection-balance' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::collectionBalance())->toThrow($exceptionClass);

})->with([['collection_balance_401.json', UnauthorizedException::class, 401],
])->group('facade');

it('can successful get collection statement', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/CollectMoney/collection_statement_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/collection-statement' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/collection-statement' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::collectionStatement([
        'startDate' => '2023-01-01',
        'endDate' => '2023-01-31',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate get collection statement', function (array $data, string $validation) {

    expect(fn () => TemboFacade::collectionStatement($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The start date field is required.","The end date field is required."]'],
])->group('facade');

it('can throw error during get collection statement', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/CollectMoney/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/wallet/collection-statement' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/wallet/collection-statement' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::collectionStatement([
        'startDate' => '2023-01-01',
        'endDate' => '2023-01-31',
    ]))->toThrow($exceptionClass);

})->with([['collection_statement_401.json', UnauthorizedException::class, 401],
    ['collection_statement_400.json', BadRequestException::class, 400],
])->group('facade');

it('can successful get collection payment status', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/CollectMoney/collection_status_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/collection/status' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/collection/status' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::collectionPaymentStatus([
        'transactionRef' => 'Hyu8373HmsI',
        'transactionId' => 'X50jcLDcU',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate get collection payment status', function (array $data, string $validation) {

    expect(fn () => TemboFacade::collectionPaymentStatus($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The transaction id field is required.","The transaction ref field is required."]'],
])->group('facade');

it('can throw error during get collection payment status', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/CollectMoney/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/tembo/v1/collection/status' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/tembo/v1/collection/status' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::collectionPaymentStatus([
        'transactionRef' => 'Hyu8373HmsI',
        'transactionId' => 'X50jcLDcU',
    ]))->toThrow($exceptionClass);

})->with([['collection_status_401.json', UnauthorizedException::class, 401],
])->group('facade');
