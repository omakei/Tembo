<?php

namespace Omakei\Tembo\Traits;

use Exception;
use Illuminate\Http\Client\Response;
use Omakei\Tembo\Exceptions\BadGatewayException;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\ConflictException;
use Omakei\Tembo\Exceptions\NotFoundException;
use Omakei\Tembo\Exceptions\UnauthorizedException;
use Omakei\Tembo\Traits\Validation\CollectMoneyValidation;

trait CollectMoney
{
    use CollectMoneyValidation;

    /**
     * Collect from Mobile Money
     * Collect money from a mobile subscriber through a USSD push request
     *
     * @param  array  $data  {
     *                       'channel': string,
     *                       'amount': float,
     *                       'msisdn': string,
     *                       'narration': string,
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
    public function sendAUSSDPushRequest(array $data): ?array
    {
        $this->validateSendAUSSDPushRequestInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/collection', [...$data, 'callbackUrl' => route('wallet_to_mobile_callback')])
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
     * Collection Balance
     * Retrieves the balance of your collection account
     *
     * @return array {
     *               'statusCode': string,
     *               'transactionRef': string,
     *               'transactionId': string,
     *
     * } | {
     *    'availableBalance': float,
     *    'currentBalance':float,
     *    'accountNo':string,
     *    'accountStatus':string,
     *    'accountName': string
     *    }
     *
     * @throws Exception
     */
    public function collectionBalance(): ?array
    {
        // $this->validateSendAUSSDPushRequestInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/wallet/collection-balance', [])
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
     * Collection Statement
     * Check the account statement of your collection account
     *
     * @param  array  $data  {
     *                       'startDate': date,
     *                       'endDate': date,
     *                       }
     * @return array {
     *               'statusCode': int,
     *               'reason': string,
     *               'details': {
     *               'walletId': string,
     *               'amount': string,
     *               } |{
     *               {
     *               'accountNo': string,
     *               'debitOrCredit': string,
     *               'tranRefNo': string,
     *               'narration': string,
     *               'txnDate': date,
     *               'valueDate': date,
     *               'amountCredited': float,
     *               'amountDebited': float,
     *               'balance': float,
     *               }
     *               }
     *
     * @throws Exception
     */
    public function collectionStatement(array $data): ?array
    {
        $this->validateCollectionStatementInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/wallet/collection-statement', $data)
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
    public function collectionPaymentStatus(array $data): ?array
    {
        $this->validateCollectionPaymentStatusInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/collection/status', $data)
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
