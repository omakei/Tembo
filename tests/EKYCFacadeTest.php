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

it('can successful initiate onboard request', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/EKYC/onboard_request_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/onboard/v1/onboard' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/onboard/v1/onboard' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::initiateOnboardRequest([
        'nin' => 'XXXXXXXXXXXXX',
        'phoneNumber' => '2557183987655',
        'email' => 'john.doe@example.com',
        'cardIssueDate' => '2010-01-19',
        'cardExpiryDate' => '2024-06-20',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate initiate onboard request', function (array $data, string $validation) {

    expect(fn () => TemboFacade::initiateOnboardRequest($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The nin field is required.","The phone number field is required.","The email field is required.","The card issue date field is required.","The card expiry date field is required."]'],
])->group('facade');

it('can successful retrieve first question', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/EKYC/first_question_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/onboard/v1/onboard/verify' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/onboard/v1/onboard/verify' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::retrieveFirstQuestion([
        'onboardId' => '1JpXezddTbXH',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate retrieve first question', function (array $data, string $validation) {

    expect(fn () => TemboFacade::retrieveFirstQuestion($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The onboard id field is required."]'],
])->group('facade');

it('can throw error during retrieve first question', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/EKYC/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/onboard/v1/onboard/verify' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/onboard/v1/onboard/verify' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::retrieveFirstQuestion([
        'onboardId' => '1JpXezddTbXH',
    ]))->toThrow($exceptionClass);

})->with([['first_question_401.json', UnauthorizedException::class, 401], ['first_question_400.json', BadRequestException::class, 400],
])->group('facade');

it('can successful reply to question', function () {
    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/EKYC/reply_question_200.json'),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/onboard/v1/onboard/verify' => Http::response($stub, 200),
        Tembo::SANDBOX_BASE_URL.'/onboard/v1/onboard/verify' => Http::response($stub, 200),
    ]);

    $data = TemboFacade::replyToAQuestion([
        'onboardId' => 'lS2Ju1biJuug',
        'questionCode' => '106',
        'answer' => '1997',
    ]);

    $this->assertEquals($data, $stub);
})->group('facade');

it('can successful validate reply to question', function (array $data, string $validation) {

    expect(fn () => TemboFacade::replyToAQuestion($data))->toThrow(new Exception($validation));

})->with([
    ['data' => [], 'validation' => '["The onboard id field is required.","The question code field is required.","The answer field is required."]'],
])->group('facade');

it('can throw error during reply to question', function (string $stubFile, string $exceptionClass, int $statusCode) {

    $stub = json_decode(
        file_get_contents(__DIR__.'/Stubs/Response/EKYC/'.$stubFile),
        true
    );

    Http::fake([
        Tembo::BASE_URL.'/onboard/v1/onboard/verify' => Http::response($stub, $statusCode),
        Tembo::SANDBOX_BASE_URL.'/onboard/v1/onboard/verify' => Http::response($stub, $statusCode),
    ]);

    expect(fn () => TemboFacade::replyToAQuestion([
        'onboardId' => 'lS2Ju1biJuug',
        'questionCode' => '106',
        'answer' => '1997',
    ]))->toThrow($exceptionClass);

})->with([['reply_question_401.json', UnauthorizedException::class, 401], ['reply_question_400.json', BadRequestException::class, 400],
])->group('facade');
