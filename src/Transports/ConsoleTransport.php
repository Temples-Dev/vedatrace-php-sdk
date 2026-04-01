<?php

namespace VedaTrace\Transports;

class ConsoleTransport implements TransportInterface
{
    private string $format;

    public function __construct(array $options = [])
    {
        $this->format = $options['format'] ?? 'json';
    }

    public function send(array $logs): void
    {
        foreach ($logs as $log) {
            $output = $this->format === 'json' 
                ? json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) 
                : $this->prettyPrint($log);
            
            file_put_contents('php://stdout', $output . PHP_EOL);
        }
    }

    private function prettyPrint(array $log): string
    {
        $timestamp = $log['timestamp'] ?? date('Y-m-d H:i:s');
        $level = strtoupper($log['level'] ?? 'INFO');
        $message = $log['message'] ?? '';
        $service = $log['service'] ?? 'unknown';
        
        $meta = $log;
        unset($meta['timestamp'], $meta['level'], $meta['message'], $meta['service']);
        
        $metaStr = !empty($meta) ? ' ' . json_encode($meta, JSON_UNESCAPED_SLASHES) : '';
        
        return sprintf("[%s] %s (%s): %s%s", $timestamp, $level, $service, $message, $metaStr);
    }
}
