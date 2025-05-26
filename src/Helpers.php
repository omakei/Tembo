<?php

namespace Omakei\Tembo;

class Helpers
{
    /**
     * Cleans the mobile number to remove any whitespace or dashes
     *
     * @param  string  $mobileNumber
     */
    public static function cleanMobileNumber($mobileNumber): string
    {
        $mobileNumber = preg_replace('/[^0-9]/', '', $mobileNumber);

        if (strlen($mobileNumber) < 9 || strlen($mobileNumber) > 12) {
            throw new \Exception('Invalid mobile number');
        }
        if (strlen($mobileNumber) == 9 && $mobileNumber[0] != '0') {
            $mobileNumber = "255{$mobileNumber}";
        } elseif (strlen($mobileNumber) == 10 && $mobileNumber[0] == '0') {
            $mobileNumber = str_replace('0', '255', $mobileNumber, $count);
        }

        return $mobileNumber;
    }

    /**
     * Clean the amount to remove any whitespace or commas
     *
     * @param  string  $amount
     */
    public static function cleanAmount($amount): string
    {
        $amount = trim($amount);
        $amount = str_replace(' ', '', $amount);
        $amount = str_replace(',', '', $amount);

        return $amount;
    }

    public static function verifySignature(string $secretBase64, string $timestamp, array $body, string $receivedSignature): bool
    {
        // Decode the base64 secret
        $secret = base64_decode($secretBase64);

        // Reconstruct the concatenated string
        $concatenatedString = $timestamp.
            $body['accountNo'].
            $body['id'].
            $body['transactionId'].
            $body['reference'].
            $body['transactionType'].
            $body['channel'].
            $body['transactionDate'].
            $body['postingDate'].
            $body['valueDate'].
            $body['narration'].
            $body['currency'].
            intval($body['amountCredit']).
            intval($body['amountDebit']).
            intval($body['clearedBalance']).
            intval($body['bookedBalance']);

        // Compute the HMAC using SHA-256 and base64-encode the result
        $computedSignature = base64_encode(hash_hmac('sha256', $concatenatedString, $secret, true));
        // dd($computedSignature, hash_equals($computedSignature, $receivedSignature));
        logger($computedSignature, ['secret' => $secretBase64, 'timestamp' => $timestamp, 'body' => $body, 'receivedSignature' => $receivedSignature]);

        // Compare the computed signature with the one received
        return hash_equals($computedSignature, $receivedSignature);
    }
}
