<?php

namespace VedaTrace;

use Psr\Log\AbstractLogger;
use VedaTrace\Transports\TransportInterface;

class Logger extends AbstractLogger
{
    private Config $config;
    /** @var TransportInterface[] */
    private array $transports;
    private array $defaultMetadata;
    private array $buffer = [];
    private Redactor $redactor;
    private bool $isDisposed = false;

    public function __construct(Config $config, array $transports, array $defaultMetadata = [])
    {
        $this->config = $config;
        $this->transports = $transports;
        $this->defaultMetadata = $defaultMetadata;
        $this->redactor = new Redactor($config->redaction);
        
        // Register shutdown function to flush logs on process exit
        register_shutdown_function([$this, 'flush']);
    }

    public function log($level, $message, array $context = []): void
    {
        if ($this->isDisposed) {
            return;
        }

        $logEntry = $this->formatLog($level, (string) $message, $context);
        
        $this->buffer[] = $logEntry;

        if (count($this->buffer) >= $this->config->batchSize) {
            $this->flush();
        }
    }

    public function child(array $metadata): self
    {
        return new self(
            $this->config,
            $this->transports,
            array_merge($this->defaultMetadata, $metadata)
        );
    }

    public function warn($message, array $context = []): void
    {
        $this->warning($message, $context);
    }

    public function fatal($message, array $context = []): void
    {
        $this->log('fatal', $message, $context);
    }

    public function flush(): void
    {
        if (empty($this->buffer)) {
            return;
        }

        $batch = $this->buffer;
        $this->buffer = [];

        foreach ($this->transports as $transport) {
            $transport->send($batch);
        }
    }

    public function dispose(): void
    {
        if ($this->isDisposed) {
            return;
        }

        $this->flush();
        $this->isDisposed = true;
    }

    private function formatLog($level, string $message, array $context): array
    {
        // Normalize PSR-3 levels to VedaTrace expected levels
        $levelMap = [
            'warning' => 'warn',
            'critical' => 'fatal',
            'alert' => 'fatal',
            'emergency' => 'fatal',
            'notice' => 'info',
            'debug' => 'info' // TEMPORARY MAP TO TEST IF 'debug' IS THE CAUSE
        ];
        $vtLevel = $levelMap[strtolower($level)] ?? strtolower($level);

        $metadata = array_merge($this->defaultMetadata, $context);
        $metadata = $this->redactor->redact($metadata);
        
        // Extract standard reserved keys from metadata if they exist
        $service = $metadata['service'] ?? $this->config->service;
        $environment = $metadata['environment'] ?? $this->config->environment;

        // Remove them so they don't duplicate inside the metadata object
        unset($metadata['service'], $metadata['environment']);

        $log = [
            'level' => $vtLevel,
            'message' => $this->interpolate($message, $metadata), // Use redacted metadata for interpolation
            'timestamp' => date('c'),
            'service' => $service,
            'environment' => $environment,
            'metadata' => $metadata
        ];

        return $log;
    }

    private function interpolate(string $message, array $context): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (is_scalar($val) || (is_object($val) && method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }
}
