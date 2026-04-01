<?php

require_once __DIR__ . '/vendor/autoload.php';

use function VedaTrace\vedatrace;

/**
 * 💡 Welcome to the VedaTrace Standalone PHP Example!
 * 
 * This example shows how to use VedaTrace in a plain PHP environment.
 * Make sure to run `composer install` in this directory first.
 */

// 1. Initialize the logger with your API Key
$logger = vedatrace([
    'apiKey' => 'your-api-key',
    'service' => 'standalone-php-example',
    'transport' => 'console', // Defaults to 'http' for production
    'prettyPrint' => true     // Great for local development visibility
]);

// 2. Start logging!
echo "📝 Sending logs to VedaTrace...\n";

$logger->info('Application started', [
    'runtime' => phpversion(),
    'os' => PHP_OS
]);

// 3. Structured context with sensitive data redaction
$logger->debug('User session check', [
    'user_id' => 123,
    'session_token' => 'abc.def.ghi', // This will be automatically redacted if configured!
    'password' => 'secret123'         // This is always redacted by default
]);

// 4. Create a child logger for scoped context (e.g., within a request lifecycle)
$requestId = 'req-' . uniqid();
$scopedLogger = $logger->child(['request_id' => $requestId]);
$scopedLogger->warn('Processing some data in scope');

// 5. Always call dispose() at the end to ensure all logs are flushed!
$logger->dispose();

echo "✅ Logs flushed and logger disposed.\n";
