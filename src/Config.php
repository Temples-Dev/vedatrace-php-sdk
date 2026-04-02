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
        $this->apiKey = $options['apiKey'] ?? $this->getEnvVar('VEDATRACE_API_KEY') ?: '';
        $this->service = $options['service'] ?? $this->getEnvVar('VEDATRACE_SERVICE') ?: 'default-php-service';
        $this->endpoint = $options['endpoint'] ?? $this->getEnvVar('VEDATRACE_ENDPOINT') ?: 'https://ingest.vedatrace.dev/v1/logs';
        $this->environment = $options['environment'] ?? $this->getEnvVar('VEDATRACE_ENVIRONMENT') ?: 'production';
        $this->batchSize = $options['batchSize'] ?? (int) $this->getEnvVar('VEDATRACE_BATCH_SIZE') ?: 100;
        $this->flushInterval = $options['flushInterval'] ?? (int) $this->getEnvVar('VEDATRACE_FLUSH_INTERVAL') ?: 5000;
        $this->maxRetries = $options['maxRetries'] ?? (int) $this->getEnvVar('VEDATRACE_MAX_RETRIES') ?: 3;
        $this->retryDelay = $options['retryDelay'] ?? (int) $this->getEnvVar('VEDATRACE_RETRY_DELAY') ?: 1000;
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

    private function getEnvVar(string $name)
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        return getenv($name) !== false ? getenv($name) : null;
    }
}
