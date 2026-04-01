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
            if (!empty($config->apiKey)) {
                $transports[] = new HttpTransport($options);
            } else {
                // If no apiKey and no transports, default to console for visibility
                $transports[] = new ConsoleTransport($options);
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
