<?php

namespace VedaTrace;

use VedaTrace\Transports\ConsoleTransport;
use VedaTrace\Transports\HttpTransport;

class VedaTrace
{
    /**
     * @param array $options Configuration options.
     * @return Logger
     */
    public static function create(array $options = []): Logger
    {
        $config = new Config($options);
        $transports = $options['transports'] ?? [];

        if (empty($transports)) {
            $transportOptions = array_merge($options, [
                'apiKey' => $config->apiKey,
                'endpoint' => $config->endpoint
            ]);

            if (!empty($config->apiKey)) {
                $transports[] = new HttpTransport($transportOptions);
            } else {
                // If no apiKey and no transports, default to console for visibility
                $transports[] = new ConsoleTransport($transportOptions);
            }
        }

        return new Logger($config, $transports, $options['defaultMetadata'] ?? []);
    }
}

/**
 * Helper function like the JavaScript API
 */
if (!function_exists('vedatrace')) {
    function vedatrace(array $options = []): Logger
    {
        return VedaTrace::create($options);
    }
}
