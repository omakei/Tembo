<?php

use Illuminate\Support\Facades\Event;
use Omakei\Tembo\Tembo;
use Illuminate\Support\Facades\Http;
use Omakei\Tembo\Events\RemittanceCallback;
use Omakei\Tembo\Events\TemboCallback;
use Omakei\Tembo\Events\UtilityPaymentsCallback;
use Omakei\Tembo\Events\WalletToMobileCallback;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\ConflictException;
use Omakei\Tembo\Exceptions\NotFoundException;
use Omakei\Tembo\Exceptions\UnauthorizedException;

beforeEach(function () {
    config(['tembo.accountId' => '123456789']);
    config(['tembo.secretKey' => '123456789']);
    
});

it('merchant account callback can successful be executed', function () {
    Event::fake();
    $secretRaw = config('tembo.secretKey');
    $secretBase64 = base64_encode(config('tembo.secretKey'));
    putenv("CALLBACK_SECRET={$secretBase64}");

    $timestamp = now()->format('YmdHis');
    $payload = [
        'accountNo' => '1234567890',
        'payerName' => 'TEMBOPLUS COMPANY LIMITED',
        'id' => 'abc123',
        'transactionId' => 'txn789',
        'reference' => 'ref001',
        'transactionType' => 'CREDIT',
        'channel' => 'MOBILE',
        'transactionDate' => '2025-05-26',
        'postingDate' => '2025-05-26',
        'valueDate' => '2025-05-26',
        'narration' => 'Payment for services',
        'currency' => 'USD',
        'amountCredit' => 1000.55,
        'amountDebit' => 0.00,
        'clearedBalance' => 5000.75,
        'bookedBalance' => 6000.30,
    ];

    $concatenatedString = $timestamp .
        $payload['accountNo'] .
        $payload['id'] .
        $payload['transactionId'] .
        $payload['reference'] .
        $payload['transactionType'] .
        $payload['channel'] .
        $payload['transactionDate'] .
        $payload['postingDate'] .
        $payload['valueDate'] .
        $payload['narration'] .
        $payload['currency'] .
        intval($payload['amountCredit']) .
        intval($payload['amountDebit']) .
        intval($payload['clearedBalance']) .
        intval($payload['bookedBalance']);

    $computedSignature = base64_encode(hash_hmac('sha256', $concatenatedString, $secretRaw, true));
    $this->postJson('/api/v1/merchant/callback', $payload, [
        'content-type' => 'application/json',
        'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
        'x-request-timestamp' => $timestamp,
        'x-request-signature' => $computedSignature
    ])->assertStatus(200)->assertJson([
        'success' => true,
        'message' => 'Callback received successfully',
    ]);

    Event::assertDispatched(TemboCallback::class);
   
});

it('can successful validate merchant account callback ', function () {

    $this->postJson('/api/v1/merchant/callback', [], [
        'content-type' => 'application/json',
        'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
        'x-request-timestamp' => '202310261200',
        'x-request-signature' => 'Y2FsbGJhY2stc2lnbmF0dXJl'
    ])->assertStatus(422);
});

it('pay to mobile callback can successful be executed', function () {
    Event::fake();

    $this->postJson('/api/v1/wallet-to-mobile/callback', [
        "statusCode" => "PAYMENT_ACCEPTED",
        "transactionRef" => "20f807fe-3ee8-4525-8aff-ccb95de38250",
        "transactionId" => "X50jcLD-U"
    ], [
        'content-type' => 'application/json',
        'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
    ])->assertStatus(200)->assertJson([
        'success' => true,
        'message' => 'Callback received successfully',
    ]);

    Event::assertDispatched(WalletToMobileCallback::class);
   
});

it('can successful validate pay to mobile callback ', function () {

    $this->postJson('/api/v1/wallet-to-mobile/callback', [], [
        'content-type' => 'application/json',
        'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
    ])->assertStatus(422);
});

it('utility payment callback can successful be executed', function () {
    Event::fake();

    $this->postJson('/api/v1/utility-payment/callback', [
        "statusCode" => "PAYMENT_ACCEPTED",
        "transactionRef" => "20f807fe-3ee8-4525-8aff-ccb95de38250",
        "transactionId" => "X50jcLD-U"
    ], [
        'content-type' => 'application/json',
        'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
    ])->assertStatus(200)->assertJson([
        'success' => true,
        'message' => 'Callback received successfully',
    ]);

    Event::assertDispatched(UtilityPaymentsCallback::class);
   
});

it('can successful validate utility payment callback ', function () {

    $this->postJson('/api/v1/utility-payment/callback', [], [
        'content-type' => 'application/json',
        'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
    ])->assertStatus(422);
});

it('remittance callback can successful be executed', function () {
    Event::fake();

    $this->postJson('/api/v1/remittance/callback', [
            "transactionId" => "550e8400e29b41d4a716446655440000",
            "paymentDate" => "2025-02-27T10:56:00Z",
            "senderCurrency" => "USD",
            "senderAmount" => 100.00,
            "receiverCurrency" => "TZS",
            "receiverAmount" => 250000.00,
            "exchangeRate" => 2500.00,
            "transactionFee" => 2500,
            "transactionAmount" => 252500.00,
            "transactionDate" => "2025-02-18T10:00:00Z",
            "receiverAccount" => "255745908755",
            "receiverChannel" => "MOBILE",
            "institutionCode" => "VODACOM",
            "partnerReference" => "HSC8474837-VS83",
            "institutionReference" => "58577.55885.93993",
            "status" => "COMPLETED",
            "statusCode" => "PAYMENT_SUCCESS",
            "statusMessage" => "Success",
            "receiptNumber" => "RM48474558557",
            "createdAt" => "2025-02-18T10:05:00Z",
            "updatedAt" => "2025-02-18T10:05:00Z",
            "completedAt" => "2025-02-18T10:05:00Z"
    ], [
        'content-type' => 'application/json',
        'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
    ])->assertStatus(200)->assertJson([
        'success' => true,
        'message' => 'Callback received successfully',
    ]);

    Event::assertDispatched(RemittanceCallback::class);
   
});

it('can successful validate remittance callback ', function () {

    $this->postJson('/api/v1/remittance/callback', [], [
        'content-type' => 'application/json',
        'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
    ])->assertStatus(422);
});