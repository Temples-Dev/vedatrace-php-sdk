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
        $this->apiKey = $options['apiKey'] ?? getenv('VEDATRACE_API_KEY') ?: '';
        $this->service = $options['service'] ?? getenv('VEDATRACE_SERVICE') ?: 'default-php-service';
        $this->endpoint = $options['endpoint'] ?? getenv('VEDATRACE_ENDPOINT') ?: 'https://ingest.vedatrace.dev/v1/logs';
        $this->environment = $options['environment'] ?? getenv('VEDATRACE_ENVIRONMENT') ?: 'production';
        $this->batchSize = $options['batchSize'] ?? getenv('VEDATRACE_BATCH_SIZE') ?: 100;
        $this->flushInterval = $options['flushInterval'] ?? getenv('VEDATRACE_FLUSH_INTERVAL') ?: 5000;
        $this->maxRetries = $options['maxRetries'] ?? getenv('VEDATRACE_MAX_RETRIES') ?: 3;
        $this->retryDelay = $options['retryDelay'] ?? getenv('VEDATRACE_RETRY_DELAY') ?: 1000;
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
