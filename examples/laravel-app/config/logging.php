<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Channel
    |--------------------------------------------------------------------------
    |
    | Define the default log channel for your application.
    |
    */
    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application.
    |
    */
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'vedatrace'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'vedatrace' => [
            'driver' => 'monolog',
            'handler' => VedaTrace\Laravel\VedaTraceHandler::class,
            'level' => 'info',
        ],
    ],
];
