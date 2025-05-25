<?php

namespace Omakei\Tembo\Traits;

use Exception;
use Illuminate\Http\Client\Response;
use Omakei\Tembo\Exceptions\BadGatewayException;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\ConflictException;
use Omakei\Tembo\Exceptions\NotFoundException;
use Omakei\Tembo\Exceptions\UnauthorizedException;
use Omakei\Tembo\Traits\Validation\MerchantVirtualAccountsValidation;

trait MerchantVirtualAccounts
{
    use MerchantVirtualAccountsValidation;

    /**
     * Create Merchant Virtual Account
     *
     * @param  array  $data  {
     *                       'companyName': string,
     *                       'reference': string,
     *                       }
     * @return array {
     *   'id': string,
     *   'accountName': string,
     *   'accountNo': string,
     *  'reference': string,
     * }| array{
     *   'companyName': string,
     * } | array{
     *   'message': string,
     *   'error': string,
     *    'statusCode': int
     * }
     *
     * @throws Exception
     */
    public function createMerchantVirtualAccount(array $data): ?array
    {
        $this->validateCreateMerchantVirtualAccountInput($data);

        $response = $this->sendRequestUsingBearerToken('post', '/account', $data)
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

                if($response->notFound()) {
                    throw new NotFoundException($response);
                }

                if($response->status() === 502) {
                    throw new BadGatewayException($response);
                }
            });

        return $response->json();
    }

    /**
     * Get Account Balance
     *
     * @param  array  $data  {
     *                       'accountNo': string,
     *                       }
     * @return array  {
     * 'success': bool,
     * 'message': string,
     * 'result': {
     *   'accountNo': string,
     *   'accountName': string,
     *   'branchCode': string,
     *   'availableBalance': int,
     *   'bookedBalance':  int
     * }
     *  }| string | array{
     *    'statusCode': int,
     *    'message': string,
     *    'error': string,
     *    'details': {
     *        'accountNo': string,
     *    }
     *    }
     *
     * @throws Exception
     */
    public function getAccountBalance(array $data): ?array
    {
        $this->validateGetAccountBalanceInput($data);

        $response = $this->sendRequestUsingBearerToken('post', '/account/balance', $data)
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

                if($response->notFound()) {
                    throw new NotFoundException($response);
                }

                if($response->status() === 502) {
                    throw new BadGatewayException($response);
                }
            });

        return $response->json();
    }

    /**
     * Get Account Statement
     *
     * @param  array  $data  {
     *                       'accountNo': string,
     *                       'startDate': string,
     *                       'endDate': string,
     *                       }
     * @return array  {
     * 'success': bool,
     * 'message': string,
     * 'result': {
     *   'accountNo': string,
     *   'statement': {
     *    'id': string,
     *    'transactionId': string,
     *    'reference': string,
     *    'transactionType': string,
     *    'channel': string,
     *    'transactionDate': string,
     *    'postingDate': string,
     *    'valueDate': string,
     *    'narration': string,
     *    'currency': string,
     *    'amountCredit': float,
     *    'amountDebit': float,
     *    'clearedBalance': float,
     *    'bookedBalance': float,
     * },
     * }
     *  }| string | array{
     *    'statusCode': int,
     *    'message': string,
     *    'error': string,
     *    'details': {
     *        'endDate': string,
     *    }
     *    }
     *
     * @throws Exception
     */
    public function getAccountStatement(array $data): ?array
    {
        $this->validateGetAccountStatementInput($data);

        $response = $this->sendRequestUsingBearerToken('post', '/account/statement', $data)
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

                if($response->notFound()) {
                    throw new NotFoundException($response);
                }

                if($response->status() === 502) {
                    throw new BadGatewayException($response);
                }
            });

        return $response->json();
    }
}
