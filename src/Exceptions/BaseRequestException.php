<?php

namespace Omakei\Tembo\Exceptions;

/**
 * Class BaseRequestException.
 *
 * Base Exception for use in filtering a response for information.
 */
class BaseRequestException extends \Exception
{
    public $requestId = '';

    public $reason;

    public $error;

    public $details;

    public $statusCode;

    public $message;

    public $response;

    /**
     * BaseRequestException constructor.
     *
     * @param  \Illuminate\Http\Client\Response  $response
     * @param  null|string  $message  Exception message
     */
    public function __construct($response, $message = null)
    {
        $this->response = $response;

        $responseJson = $response->json();

        if (! empty($responseJson['statusCode'])) {
            $this->statusCode = $responseJson['statusCode'];
        }
        if (! empty($responseJson['reason'])) {
            $this->reason = $responseJson['reason'];
        }
        if (! empty($responseJson['details'])) {
            $this->details = $responseJson['details'];
        }
        if (! empty($responseJson['error'])) {
            $this->error = $responseJson['error'];
        }
        if (! empty($responseJson['message'])) {
            $this->message = $responseJson['message'];
        }

        $this->filterResponseForException($response);

        if (isset($message)) {
            $this->message = $message;
        }
    }

    private function filterResponseForException($response)
    {
        try {
            $responseBody = $response->body;

            $this->message = $responseBody;
        } catch (\Exception $e) {
            $this->message = '';
        }

        if (\array_key_exists('x-request-id', $response->headers())) {
            $this->requestId = $response->headers['x-request-id'];
        }
    }
}
