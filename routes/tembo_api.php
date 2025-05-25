<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Omakei\Tembo\Events\RemittanceCallback;
use Omakei\Tembo\Events\TemboCallback;
use Omakei\Tembo\Events\UtilityPaymentsCallback;
use Omakei\Tembo\Events\WalletToMobileCallback;
use Omakei\Tembo\Helpers;

Route::post('/api/v1/merchant/callback', function (Request $request) {
    // Validate the incoming request
    $request->validate([
        'accountNo' => ['required', 'string'],
        'payerName' => ['required', 'string'],
        'id' => ['required', 'string'],
        'transactionId' => ['required', 'string'],
        'reference' => ['required', 'string'],
        'transactionType' => ['required', 'string'],
        'channel' => ['required', 'string'],
        'transactionDate' => ['required', 'date'],
        'postingDate' => ['required', 'date'],
        'valueDate' => ['required', 'date'],
        'currency' => ['required', 'string', Rule::in(['TZS', 'USD', 'KES', 'UGS', 'RWF'])],
        'narration' => ['required', 'string'],
        'amountCredit' => ['required', 'float'],
        'amountDebit' => ['required', 'float'],
        'clearedBalance' => ['required', 'float'],
        'bookedBalance' => ['required', 'float'],
    ]);

    $result = Helpers::verifySignature(
        config('tembo.secretKey'),
        $request->header('x-tembo-timestamp'),
        $request->all(),
        $request->header('x-tembo-signature')
    );

    if (! $result) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid signature',
        ], 401);
    }

    TemboCallback::dispatch($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Callback received successfully',
    ]);

})->name('merchant_callback');

Route::post('/api/v1/wallet-to-mobile/callback', function (Request $request) {
    // Validate the incoming request
    $request->validate([
        'statusCode' => ['required', 'string'],
        'transactionRef' => ['required', 'string'],
        'transactionId' => ['required', 'string'],
    ]);

    WalletToMobileCallback::dispatch($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Callback received successfully',
    ]);

})->name('wallet_to_mobile_callback');

Route::post('/api/v1/utility-payment/callback', function (Request $request) {
    // Validate the incoming request
    $request->validate([
        'statusCode' => ['required', 'string'],
        'transactionRef' => ['required', 'string'],
        'transactionId' => ['required', 'string'],
    ]);

    UtilityPaymentsCallback::dispatch($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Callback received successfully',
    ]);

})->name('utility_payment_callback');

Route::post('/api/v1/remittance/callback', function (Request $request) {
    // Validate the incoming request
    $request->validate([
        'transactionId' => ['required', 'string'],
        'paymentDate' => ['required', 'date'],
        'senderCurrency' => ['required', 'string', Rule::in(['TZS', 'USD', 'KES', 'UGS', 'RWF'])],
        'senderAmount' => ['required', 'numeric'],
        'receiverCurrency' => ['required', 'string', Rule::in(['TZS', 'USD', 'KES', 'UGS', 'RWF'])],
        'receiverAmount' => ['required', 'numeric'],
        'exchangeRate' => ['required', 'numeric'],
        'transactionFee' => ['required', 'numeric'],
        'transactionAmount' => ['required', 'numeric'],
        'transactionDate' => ['required', 'date'],
        'receiverAccount' => ['required', 'string'],
        'receiverChannel' => ['required', 'string'],
        'institutionCode' => ['required', 'string'],
        'partnerReference' => ['required', 'string'],
        'institutionReference' => ['required', 'string'],
        'status' => ['required', 'string'],
        'statusCode' => ['required', 'string'],
        'statusMessage' => ['required', 'string'],
        'statusCode' => ['required', 'string'],
        'receiptNumber' => ['required', 'string'],
        'createdAt' => ['required', 'date'],
        'updatedAt' => ['required', 'date'],
        'completedAt' => ['required', 'date'],

    ]);

    RemittanceCallback::dispatch($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Callback received successfully',
    ]);

})->name('remittance_callback');
