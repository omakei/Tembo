<?php

namespace Omakei\Tembo\Traits;

use Exception;
use Illuminate\Http\Client\Response;
use Omakei\Tembo\Exceptions\BadGatewayException;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\ConflictException;
use Omakei\Tembo\Exceptions\NotFoundException;
use Omakei\Tembo\Exceptions\UnauthorizedException;
use Omakei\Tembo\Traits\Validation\MakePaymentValidation;

trait MakePayment
{
    use MakePaymentValidation;

    /**
     * Wallet to Mobile and Wallet to Other Banks
     * This endpoint facilitates the transfer of funds from either a customer wallet or your disbursement wallet to a mobile subscriber.
     *
     * @param  array  $data  {
     *                       'countryCode': string,
     *                       'accountNo': string,
     *                       'serviceCode': string,
     *                       'amount': float,
     *                       'msisdn': string,
     *                       'narration': string,
     *                       'currencyCode': string,
     *                       'recipientNames': string,
     *                       'transactionRef': string,
     *                       'transactionDate': date,
     *                       'callbackUrl': string
     *                       }
     * @return array {
     *               'statusCode': string,
     *               'transactionRef': string,
     *               'transactionId': string,
     *
     * } | {
     *
     *   'reason': string,
     *   'statusCode': int,
     *  'details':{
     *   'firstName': string,
     *   'dateOfBirth': string,
     *   'address.city': string,
     * }
     * }
     *
     * @throws Exception
     */
    public function walletToMobile(array $data): ?array
    {
        $this->validateWalletToMobileInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/payment/wallet-to-mobile', [...$data, 'callbackUrl' => route('wallet_to_mobile_callback')])
            ->onError(function (Response $response) {
                if ($response->badRequest()) {
                    throw new BadRequestException($response);
                }

                if ($response->unauthorized()) {
                    throw new UnauthorizedException($response);
                }

                if ($response->conflict()) {
                    throw new ConflictException($response);
                }

                if ($response->serverError()) {
                    throw new Exception('There is a problem with payment processing server.');
                }

                if ($response->notFound()) {
                    throw new NotFoundException($response);
                }

                if ($response->status() === 502) {
                    throw new BadGatewayException($response);
                }

            });

        return $response->json();
    }

    /**
     * Utility Payments
     * This endpoint facilitates the transfer of funds from either a customer wallet or your disbursement wallet to a mobile subscriber.
     *
     * @param  array  $data  {
     *                       'countryCode': string,
     *                       'accountNo': string,
     *                       'serviceCode': string,
     *                       'amount': float,
     *                       'msisdn': string,
     *                       'narration': string,
     *                       'currencyCode': string,
     *                       'recipientNames': string,
     *                       'transactionRef': string,
     *                       'transactionDate': date,
     *                       'callbackUrl': string
     *                       }
     * @return array {
     *               'status': string,
     *               'reference': string,
     *               'description': string,
     *               'meta': {
     *               'name': string,
     *               'amount': float,
     *               'institution': null,
     *               'type': string,
     *               'desc': string,
     *               'chargeType': null,
     *               'charge': null
     *
     * }
     *
     * } | {
     *
     *   'reason': string,
     *   'statusCode': int,
     *  'details':{
     *   'firstName': string,
     *   'dateOfBirth': string,
     *   'address.city': string,
     * }
     * }
     *
     * @throws Exception
     */
    public function utilityPayments(array $data): ?array
    {
        $this->validateUtilityPaymentsInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/payment/biller', [...$data, 'callbackUrl' => route('utility_payment_callback')])
            ->onError(function (Response $response) {
                if ($response->badRequest()) {
                    throw new BadRequestException($response);
                }

                if ($response->unauthorized()) {
                    throw new UnauthorizedException($response);
                }

                if ($response->conflict()) {
                    throw new ConflictException($response);
                }

                if ($response->serverError()) {
                    throw new Exception('There is a problem with payment processing server.');
                }

                if ($response->notFound()) {
                    throw new NotFoundException($response);
                }

                if ($response->status() === 502) {
                    throw new BadGatewayException($response);
                }
            });

        return $response->json();
    }

    /**
     * Payment Status
     * Use this endpoint to check the current status of a payment.
     *
     * @param  array  $data  {
     *                       'transactionId': string,
     *                       'transactionRef': string,
     *                       }
     * @return array {
     *               'statusCode': string,
     *               'transactionId': string,
     *               'transactionRef': string,
     *
     * } | {
     *
     *   'reason': string,
     *   'statusCode': int,
     *  'details':{
     *   'firstName': string,
     *   'dateOfBirth': string,
     *   'address.city': string,
     * }
     * }
     *
     * @throws Exception
     */
    public function utilityPaymentStatus(array $data): ?array
    {
        $this->validatePaymentStatusInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/payment/status', $data)
            ->onError(function (Response $response) {
                if ($response->badRequest()) {
                    throw new BadRequestException($response);
                }

                if ($response->unauthorized()) {
                    throw new UnauthorizedException($response);
                }

                if ($response->conflict()) {
                    throw new ConflictException($response);
                }

                if ($response->serverError()) {
                    throw new Exception('There is a problem with payment processing server.');
                }

                if ($response->notFound()) {
                    throw new NotFoundException($response);
                }

                if ($response->status() === 502) {
                    throw new BadGatewayException($response);
                }
            });

        return $response->json();
    }
}
