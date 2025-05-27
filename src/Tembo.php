<?php

namespace Omakei\Tembo;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Tembo
{
    use Traits\BankingAndWallets,
        Traits\CollectMoney,
        Traits\EKYC,
        Traits\MakePayment,
        Traits\MerchantVirtualAccounts,
        Traits\Remittance;

    const SANDBOX_BASE_URL = 'https://sandbox.temboplus.com';

    const BASE_URL = 'https:/temboplus.com';

    private string $baseUrl;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (empty(config('tembo.accountId'))) {
            throw new \InvalidArgumentException('Missing required option: "accountId" in the tembo config file');
        }

        if (empty(config('tembo.secretKey'))) {
            throw new \InvalidArgumentException('Missing required option: "secretKey" in the tembo config file');
        }

        if (empty(config('tembo.token'))) {
            throw new \InvalidArgumentException('Missing required option: "token" in the tembo config file');
        }

        $this->baseUrl = config('tembo.environment') === 'sandbox' ? self::SANDBOX_BASE_URL : self::BASE_URL;

    }

    /**
     * Prepare request to be sent to Tembo
     */
    private function sendRequestUsingSecretKey(string $method, string $uri, array $data): Response
    {
        return Http::withHeaders([
            'x-secret-key' => config('tembo.secretKey'),
            'x-account-id' => config('tembo.accountId'),
            'Content-Type' => 'application/json',
            'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
        ])->$method($this->baseUrl.$uri, $data);
    }

    /**
     * Prepare disbursement request to be sent to Tembo
     */
    private function sendRequestUsingBearerToken(string $method, string $uri, ?array $data = null): Response
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer '.config('tembo.token'),
            'Content-Type' => 'application/json',
            'x-request-id' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
        ])->$method($this->baseUrl.$uri, $data);
    }
}
