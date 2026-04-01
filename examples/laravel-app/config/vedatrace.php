<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VedaTrace API Key
    |--------------------------------------------------------------------------
    |
    | Get your API Key from your VedaTrace dashboard.
    |
    */
    'apiKey' => env('VEDATRACE_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Service Name
    |--------------------------------------------------------------------------
    |
    | Identifying name for your application.
    |
    */
    'service' => env('VEDATRACE_SERVICE', config('app.name', 'laravel-app')),

    /*
    |--------------------------------------------------------------------------
    | Batching and Performance
    |--------------------------------------------------------------------------
    |
    | How many logs should be buffered before sending them in a single batch.
    |
    */
    'batchSize' => env('VEDATRACE_BATCH_SIZE', 5),

    /*
    |--------------------------------------------------------------------------
    | Protected Fields (Redaction)
    |--------------------------------------------------------------------------
    |
    | JSON paths that will be automatically masked in log context.
    |
    */
    'redactPaths' => [
        'password',
        'token',
        'credit_card.number'
    ],
];
