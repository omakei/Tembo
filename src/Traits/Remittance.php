<?php

namespace Omakei\Tembo\Traits;

use Exception;
use Illuminate\Http\Client\Response;
use Omakei\Tembo\Exceptions\BadGatewayException;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\ConflictException;
use Omakei\Tembo\Exceptions\ForbiddenException;
use Omakei\Tembo\Exceptions\NotFoundException;
use Omakei\Tembo\Exceptions\RateLimitException;
use Omakei\Tembo\Exceptions\RateLimitException;
use Omakei\Tembo\Exceptions\UnauthorizedException;
use Omakei\Tembo\Traits\Validation\RemittanceValidation;

trait Remittance
{
    use RemittanceValidation;

    /**
     * Create Remittance
     *
     * @param  array  $data  {
     *                       'paymentDate': date,
     *                       'senderCurrency': string,
     *                       'senderAmount': float,
     *                       'receiverCurrency': string,
     *                       'receiverAmount": float,
     *                       'exchangeRate": float,
     *                       'receiverAccount": string,
     *                       'receiverChannel": string,
     *                       'institutionCode": string,
     *                       'partnerReference": string,
     *                       'callbackUrl": string,
     *                       'sender": {
     *                       'fullName': string,
     *                       'nationality': string,
     *                       'countryCode': string,
     *                       'idType': string,
     *                       'idNumber': string,
     *                       'idExpiryDate': date,
     *                       'dateOfBirth': date,
     *                       'phoneNumber': string,
     *                       'email': string,
     *                       'address': string,
     *                       'sourceOfFundsDeclaration': string,
     *                       'purposeOfTransaction': string,
     *                       'occupation': string,
     *                       'employer': string,
     *                       },
     *                       'receiver': {
     *                       'fullName': string,
     *                       'phoneNumber': string,
     *                       'email': null,
     *                       'countryCode': string
     *                       }
     *                       },
     *                       }
     * @return array{
     *   'partnerReference': string,
     *   'status': string,
     *   'statusCode': string,
     *  'statusMessage': string,
     *   'createdAt': string,
     *   'updatedAt': string,
     * }| array {
     *   'companyName': string,
     * } | array{
     *   'message': string,
     *   'error': string,
     *    'statusCode': int
     * }
     *
     * @throws Exception
     */
    public function createRemittance(array $data): ?array
    {
        $this->validateCreateRemittanceInput($data);

        $response = $this->sendRequestUsingBearerToken('post', '/remittance', [...$data, 'callbackUrl' => route('remittance_callback')])
            ->onError(function (Response $response) {
                if ($response->badRequest()) {
                    throw new BadRequestException($response);
                }

                if ($response->unauthorized()) {
                    throw new UnauthorizedException($response);
                }

                if ($response->forbidden()) {
                    throw new ForbiddenException($response);
                }

                if ($response->tooManyRequests()) {
                    throw new RateLimitException($response);
                }

                if ($response->conflict()) {
                    throw new ConflictException($response);
                }

                if ($response->serverError()) {
                    throw new Exception('There is a problem with payment processing server.');
                }

                if ($response->notFound()) {
                if ($response->notFound()) {
                    throw new NotFoundException($response);
                }

                if ($response->status() === 502) {
                if ($response->status() === 502) {
                    throw new BadGatewayException($response);
                }
            });

        return $response->json();
    }

    /**
     * Create Remittance
     *
     * @param  array  $data  {
     *                       'partnerReference': string,
     *                       }
     * @return array {
     *               'transactionId": string,
     *               'paymentDate": date,
     *               'senderCurrency": string,
     *               'senderAmount": float,
     *               'receiverCurrency": string,
     *               'receiverAmount": float,
     *               'exchangeRate": float,
     *               'transactionFee": float,
     *               'transactionAmount": float,
     *               'transactionDate": date,
     *               'receiverAccount": string,
     *               'receiverChannel": string,
     *               'institutionCode": string,
     *               'partnerReference": string,
     *               'institutionReference": string,
     *               'status": string,
     *               'statusCode": string,
     *               'statusMessage": string,
     *               'receiptNumber": string,
     *               'createdAt": date,
     *               'updatedAt": date,
     *               'completedAt": date
     *               } | array {
     *               'message': string,
     *               'error': string,
     *               'details': {
     *               'field': string,
     *               'message': string,}
     *               }
     *
     * @throws Exception
     */
    public function remittanceTransactionStatus(array $data): ?array
    {
        $this->validateRemittanceTransactionStatusInput($data);

        $response = $this->sendRequestUsingBearerToken('get', '/remittance/'.$data['partnerReference'].'/status')
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

                if ($response->forbidden()) {
                    throw new ForbiddenException($response);
                }

                if ($response->serverError()) {
                    throw new Exception('There is a problem with payment processing server.');
                }

                if ($response->notFound()) {
                if ($response->notFound()) {
                    throw new NotFoundException($response);
                }

                if ($response->status() === 502) {
                if ($response->status() === 502) {
                    throw new BadGatewayException($response);
                }
            });

        return $response->json();
    }
}
