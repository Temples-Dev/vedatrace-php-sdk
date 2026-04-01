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
        $log = array_merge(
            $this->defaultMetadata,
            $context,
            [
                'level' => $level,
                'message' => $this->interpolate($message, $context),
                'timestamp' => date('c'),
                'service' => $context['service'] ?? $this->config->service,
                'environment' => $this->config->environment,
            ]
        );

        // Sanitize context fields from top level
        unset($log['service'], $log['environment']);
        $log['service'] = $context['service'] ?? $this->config->service;
        $log['environment'] = $this->config->environment;

        return $this->redactor->redact($log);
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
