<?php

namespace VedaTrace;

class Config
{
    public string $apiKey;
    public string $service;
    public string $endpoint;
    public string $environment;
    public int $batchSize;
    public int $flushInterval;
    public int $maxRetries;
    public int $retryDelay;
    public array $redaction;
    /** @var callable|null */
    public $onError;
    /** @var callable|null */
    public $onSuccess;

    public function __construct(array $options = [])
    {
        $this->apiKey = $options['apiKey'] ?? '';
        $this->service = $options['service'] ?? 'default-php-service';
        $this->endpoint = $options['endpoint'] ?? 'https://ingest.vedatrace.dev/v1/logs';
        $this->environment = $options['environment'] ?? 'production';
        $this->batchSize = $options['batchSize'] ?? 100;
        $this->flushInterval = $options['flushInterval'] ?? 5000;
        $this->maxRetries = $options['maxRetries'] ?? 3;
        $this->retryDelay = $options['retryDelay'] ?? 1000;
        $this->redaction = $options['redaction'] ?? [
            'paths' => ['password', 'token', 'secret', 'authorization'],
            'mask' => '[REDACTED]'
        ];
        $this->onError = $options['onError'] ?? null;
        $this->onSuccess = $options['onSuccess'] ?? null;
    }

    public function validate(): void
    {
        if (empty($this->apiKey)) {
            // apiKey is required for HTTP transport, but might not be for console
            // We'll leave validation to the transport or the main VedaTrace factory
        }
    }
}
