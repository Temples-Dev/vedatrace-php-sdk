<?php

namespace VedaTrace\Transports;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpTransport implements TransportInterface
{
    private string $apiKey;
    private string $endpoint;
    private Client $client;
    /** @var callable|null */
    private $onError;
    /** @var callable|null */
    private $onSuccess;

    public function __construct(array $options = [])
    {
        $this->apiKey = $options['apiKey'] ?? '';
        $this->endpoint = $options['endpoint'] ?? 'https://ingest.vedatrace.dev/v1/logs';
        $this->onError = $options['onError'] ?? null;
        $this->onSuccess = $options['onSuccess'] ?? null;
        
        $this->client = new Client([
            'timeout' => 5.0,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'User-Agent' => 'vedatrace-php/1.0.0'
            ]
        ]);
    }

    public function send(array $logs): void
    {
        if (empty($logs)) {
            return;
        }

        try {
            $response = $this->client->post($this->endpoint, [
                'json' => [
                    'logs' => $logs,
                    'sentAt' => date('c')
                ]
            ]);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                if ($this->onSuccess) {
                    ($this->onSuccess)();
                }
            } else {
                $this->handleError(new \Exception("Unexpected status code: " . $response->getStatusCode()));
            }
        } catch (GuzzleException $e) {
            $this->handleError($e);
        }
    }

    private function handleError(\Throwable $e): void
    {
        if ($this->onError) {
            ($this->onError)($e);
        } else {
            // Fallback to error_log if no custom handler
            error_log("VedaTrace Transport Error: " . $e->getMessage());
        }
    }
}
