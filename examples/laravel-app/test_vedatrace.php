<?php

require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Log;
use VedaTrace\Laravel\Facades\VedaTrace;

echo "Simulating logs for VedaTrace (Bypassing Artisan)...\n";

try {
    // 1. Sending logs through standard Laravel Log facade (via Monolog handler)
    Log::info('This is an info level log sent via standard Laravel Log facade.', [
        'user_id' => 123,
        'action' => 'login_attempt'
    ]);
    
    Log::warning('This is a warning log from the Laravel application.', [
        'memory_usage' => memory_get_usage(true)
    ]);

    // 2. Sending logs through directly via the VedaTrace facade
    VedaTrace::info('Direct info log using VedaTrace facade', [
        'module' => 'billing',
        'transaction_id' => 'tx_987654321'
    ]);

    // Testing Redaction
    VedaTrace::error('Payment failed due to invalid token', [
        'error_code' => 500,
        'token' => 'sk_live_1234567890abcdef', // Should be redacted!
        'customer_email' => 'test@example.com'
    ]);
    
    // We must manually dispose or let the shutdown handler flush (Laravel usually waits for response logic)
    // To be safe in a small CLI script, let's explicitly dispose.
    VedaTrace::dispose();

    echo "Logs dispatched successfully! Check your VedaTrace dashboard.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
