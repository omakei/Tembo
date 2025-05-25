<?php

namespace Omakei\Tembo\Traits;

use Exception;
use Illuminate\Http\Client\Response;
use Omakei\Tembo\Exceptions\BadGatewayException;
use Omakei\Tembo\Exceptions\BadRequestException;
use Omakei\Tembo\Exceptions\ConflictException;
use Omakei\Tembo\Exceptions\NotFoundException;
use Omakei\Tembo\Exceptions\UnauthorizedException;
use Omakei\Tembo\Traits\Validation\EKYCValidation;

trait EKYC
{
    use EKYCValidation;

    /**
     * Initiate Onboard Request
     *
     * @param  array  $data  {
     *                       'nin': string,
     *                       'phoneNumber': string,
     *                       'email': string,
     *                       'cardIssueDate': string,
     *                       'cardExpiryDate': string,
     *                       }
     * @return array{
     *   'id': string,
     *   'applicationId': string,
     *   'nin': string,
     *   'cardIssueDate': string ,
     *   'cardExpiryDate': string ,
     *   'phoneNumber': string,
     *   'email': string,
     *   'status': string,
     *   'kycCompleted': int,
     *   'accountNo': null,
     *   'accountName': null,
     *   'customerNo': null,
     *   'kyc': null,
     *   'partnerReference': null,
     *   'requestId': string,
     *   'createdAt': string ,
     *   'updatedAt': string
     * }| string | array {
     *   'message': string,
     *   'error': string,
     *    'statusCode': int,
     * }
     *
     * @throws Exception
     */
    public function initiateOnboardRequest(array $data): ?array
    {
        $this->validateInitiateOnboardRequestInput($data);

        $response = $this->sendRequestUsingBearerToken('post', '/onboard/v1/onboard', $data)
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
     * Initiate Onboard Request
     *
     * @param  array  $data  {
     *                       'onboardId': string,
     *                       }
     * @return array{
     *   'id': string,
     *   'code': string,
     *   'result': array <string, string>,
     * }| string | array {
     *   'message': string,
     *   'error': string,
     *    'statusCode': int,
     * }
     *
     * @throws Exception
     */
    public function retrieveFirstQuestion(array $data): ?array
    {
        $this->validateRetrieveFirstQuestionInput($data);

        $response = $this->sendRequestUsingBearerToken('post', '/onboard/v1/onboard/verify', $data)
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
     * Reply to a Question
     *
     * @param  array  $data  {
     *                       'onboardId': string,
     *                       'questionCode': string,
     *                       'answer': string,
     *                       }
     * @return array {
     *               'id': string,
     *               'code': string,
     *               'result': {
     *               'questionCode': string,
     *               'nin': string,
     *               'questionEnglish': string,
     *               'questionSwahili': string,
     *               }
     *
     * }| string | array{
     *   'message': string,
     *   'error': string,
     *    'statusCode': int
     * }
     *
     * @throws Exception
     */
    public function replyToAQuestion(array $data): ?array
    {
        $this->validateReplyToAQuestionInput($data);

        $response = $this->sendRequestUsingBearerToken('post', '/onboard/v1/onboard/verify', $data)
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
