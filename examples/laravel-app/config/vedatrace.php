<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VedaTrace API Key
    |--------------------------------------------------------------------------
    |
    | Get your API key from the VedaTrace dashboard.
    |
    */
    'apiKey' => env('VEDATRACE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Default Service Name
    |--------------------------------------------------------------------------
    |
    | This name will be attached to every log unless overridden.
    |
    */
    'service' => env('VEDATRACE_SERVICE', env('APP_NAME', 'laravel-app')),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Define the environment where the logs are coming from.
    |
    */
    'environment' => env('VEDATRACE_ENV', env('APP_ENV', 'production')),

    /*
    |--------------------------------------------------------------------------
    | Batching Options
    |--------------------------------------------------------------------------
    |
    | Configure how logs are batched before being sent to the server.
    |
    */
    'batchSize' => (int) env('VEDATRACE_BATCH_SIZE', 100),
    'flushInterval' => (int) env('VEDATRACE_FLUSH_INTERVAL', 5000),

    /*
    |--------------------------------------------------------------------------
    | Redaction
    |--------------------------------------------------------------------------
    |
    | Automatically redact sensitive information from logs.
    |
    */
    'redaction' => [
        'paths' => [
            'password',
            'password_confirmation',
            'token',
            'secret',
            'authorization',
            'cookie',
            'php_auth_pw',
            'surrogate_key',
            'key',
        ],
        'mask' => '[REDACTED]',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Endpoint
    |--------------------------------------------------------------------------
    |
    | The VedaTrace ingestion endpoint.
    |
    */
    'endpoint' => env('VEDATRACE_ENDPOINT', 'https://ingest.vedatrace.dev/v1/logs'),
];
