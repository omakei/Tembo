<?php

namespace Omakei\Tembo\Traits;

use Exception;
use Illuminate\Http\Client\Response;
use Omakei\Tembo\Exceptions\BadGatewayException;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\ConflictException;
use Omakei\Tembo\Exceptions\NotFoundException;
use Omakei\Tembo\Exceptions\UnauthorizedException;
use Omakei\Tembo\Traits\Validation\BankingAndWalletsValidation;

trait BankingAndWallets
{
    use BankingAndWalletsValidation;

    /**
     * Create Wallet
     * This is the endpoint for creating wallets (accounts)
     *
     * @param  array  $data  {
     *                       'firstName': string,
     *                       'middleName': string,
     *                       'lastName': string,
     *                       'dateOfBirth': date,
     *                       'gender': string,
     *                       'identityInfo': {
     *                       'idType': string,
     *                       'idNumber': string,
     *                       'issueDate': string,
     *                       'expiryDate': string
     *                       },
     *                       'address': {
     *                       'street': string,
     *                       'city': string,
     *                       'postalCode': string
     *                       },
     *                       'mobileNo': string,
     *                       'email': string,
     *                       'currencyCode': string,
     *                       'externalCustomerRef': string
     *                       }
     * @return array {
     *               'statusCode': int,
     *               'reason': string,
     *               }| array{
     *               'accountNo': string,
     *               } | array{
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
    public function createWallet(array $data): ?array
    {
        $this->validateCreateWalletInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/wallet', $data)
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

                if($response->notFound()) {
                    throw new NotFoundException($response);
                }

                if($response->status() === 502) {
                    throw new BadGatewayException($response);
                }

                
                if ($response->serverError()) {
                    throw new Exception('There is a problem with payment processing server.');
                }

            });

        return $response->json();
    }

    /**
     * Deposit Funds
     * Deposit funds from your main account to a wallet
     *
     * @param  array  $data  {
     *                       'amount': float,
     *                       'accountNo': string,
     *                       'transactionDate': date,
     *                       'narration': string,
     *                       'externalRefNo': string,
     *                       }
     * @return array {
     *               'statusCode': int,
     *               'reason': string,
     *               'details': {
     *               'walletId': string,
     *               'amount': string,
     *               }| string |
     *               array{
     *               'paymentRef': string,
     *               'transactionId': string,
     *               'transactionRef': string,
     *               }
     *
     * @throws Exception
     */
    public function depositFunds(array $data): ?array
    {
        $this->validateDepositFundsInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/transaction/deposit', $data)
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
     * Withdraw Funds
     * Withdraw funds from your main account to a wallet
     *
     * @param  array  $data  {
     *                       'amount': float,
     *                       'accountNo': string,
     *                       'transactionDate': date,
     *                       'narration': string,
     *                       'externalRefNo': string,
     *                       }
     * @return array {
     *               'statusCode': int,
     *               'reason': string,
     *               'details': {
     *               'walletId': string,
     *               'amount': string,
     *               }| string |
     *               array{
     *               'paymentRef': string,
     *               'transactionId': string,
     *               'transactionRef': string,
     *               }
     *
     * @throws Exception
     */
    public function withdrawFunds(array $data): ?array
    {
        $this->validateDepositFundsInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/transaction/withdraw', $data)
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
     * Wallet to Wallet Transfer
     * Transfer funds between accounts.
     *
     * @param  array  $data  {
     *                       'amount': float,
     *                       'fromAccountNo': string,
     *                       'toAccountNo': string,
     *                       'transactionDate': date,
     *                       'narration': string,
     *                       'externalRefNo': string,
     *                       }
     * @return array {
     *               'statusCode': int,
     *               'reason': string,
     *               'details': {
     *               'walletId': string,
     *               'amount': string,
     *               }| string | array{
     *               'paymentRef': string,
     *               'transactionId': string,
     *               'transactionRef': string,
     *   }

     *
     * @throws Exception
     */
    public function walletTransfer(array $data): ?array
    {
        $this->validateWalletTransferInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/transaction/transfer', $data)
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
     * Wallet Balance
     * Check wallet balance.
     *
     * @param  array  $data  {
     *                       'accountNo': number,
     *                       }
     * @return array {
     *               'statusCode': int,
     *               'reason': string,
     *               'details': {
     *               'walletId': string,
     *               'amount': string,
     *               }| string | array {
     *               'availableBalance': float,
     *               'currentBalance':float,
     *               'accountNo':string,
     *               'accountStatus':string,
     *               'accountName': string
     *               }
     *
     * @throws Exception
     */
    public function walletBalance(array $data): ?array
    {
        $this->validateWalletBalanceInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/wallet/balance', $data)
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
     * Wallet Statement
     * Check Account Statement for specific dates.
     *
     * @param  array  $data  {
     *                       'accountNo': number,
     *                       'startDate': date,
     *                       'endDate': date,
     *                       }
     * @return array {
     *               'statusCode': int,
     *               'reason': string,
     *               'details': {
     *               'walletId': string,
     *               'amount': string,
     *               }| string | array{
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
    public function walletStatement(array $data): ?array
    {
        $this->validateWalletStatementInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/wallet/statement', $data)
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
     * Main Balance
     * Retrieves the balance of your main account
     *
     * @return array {
     *               'statusCode': int,
     *               'reason': string,
     *               'details': {
     *               'walletId': string,
     *               'amount': string,
     *               }| string | array{
     *               'availableBalance': float,
     *               'currentBalance':float,
     *               'accountNo':string,
     *               'accountStatus':string,
     *               'accountName': string
     *               }
     *
     * @throws Exception
     */
    public function mainBalance(): ?array
    {
        // $this->validateWalletBalanceInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/wallet/main-balance', [])
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
     * Main Statement
     * Get statement for your main account.
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
     *               }| string | array{
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
    public function mainStatement(array $data): ?array
    {
        $this->validateMainStatementInput($data);

        $response = $this->sendRequestUsingSecretKey('post', '/tembo/v1/wallet/main-statement', $data)
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
     * List Wallets
     * List all wallets
     *
     * @return array {
     *               'statusCode': int,
     *               'reason': string,
     *               'details': {
     *               'walletId': string,
     *               'amount': string,
     *               }| string | array{
     *               'accountNo':string,
     *               }
     *
     * @throws Exception
     */
    public function listWallets(): ?array
    {
        // $this->validateWalletBalanceInput($data);

        $response = $this->sendRequestUsingSecretKey('get', '/tembo/v1/wallet', [])
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
