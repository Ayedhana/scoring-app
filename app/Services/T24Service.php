<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class T24Service
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.t24.base_url', '');
        $this->apiKey = config('services.t24.api_key', '');
    }

    /**
     * Base request wrapper to call T24 endpoints with JWT/API-Key.
     * @param string $uri
     * @param array $params
     * @return array
     */
    protected function request(string $uri, array $params = []): array
    {
        $full = rtrim($this->baseUrl, '/') . '/' . ltrim($uri, '/');

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'x-api-key' => $this->apiKey,
            ])->timeout(15)->retry(2, 100)->get($full, $params);

            $response->throw();

            return $response->json();
        } catch (ConnectionException | RequestException $exception) {
            report($exception);
            throw new \RuntimeException('T24 service unavailable.');
        }
    }

    public function fetchClient(string $t24ClientId): array
    {
        return $this->request("clients/{$t24ClientId}");
    }

    public function fetchAccounts(string $t24ClientId): array
    {
        return $this->request("clients/{$t24ClientId}/accounts");
    }

    public function fetchCredits(string $t24ClientId): array
    {
        return $this->request("clients/{$t24ClientId}/credits");
    }

    public function fetchTransactions(string $t24AccountNumber, string $fromDate, string $toDate): array
    {
        return $this->request("accounts/{$t24AccountNumber}/transactions", [
            'from' => $fromDate,
            'to' => $toDate,
        ]);
    }
}
