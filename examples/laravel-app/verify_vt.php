<?php

require __DIR__ . '/vendor/autoload.php';

use VedaTrace\VedaTrace;
use Dotenv\Dotenv;

echo "--- VedaTrace Verification with App Settings ---\n";

try {
    // Load .env
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $apiKey = $_ENV['VEDATRACE_API_KEY'] ?? null;
    $service = $_ENV['VEDATRACE_SERVICE'] ?? 'laravel-app';
    $endpoint = $_ENV['VEDATRACE_ENDPOINT'] ?? 'https://ingest.vedatrace.dev/v1/logs';

    echo "Using API Key: " . ($apiKey ? "Found" : "MISSING") . "\n";
    echo "Using Service: $service\n";
    echo "Using Endpoint: $endpoint\n";

    if (!$apiKey) {
        throw new Exception("VEDATRACE_API_KEY not found in .env!");
    }

    $config = [
        'apiKey' => $apiKey,
        'service' => $service,
        'endpoint' => $endpoint,
    ];
    
    $vt = VedaTrace::create($config);
    echo "[PASS] VedaTrace instance created.\n";

    $message = "Demo log with metadata (Automatic Redaction Test)";
    $metadata = [
        "user_id" => 1337,
        "email" => "dev@vedatrace.dev",
        "action" => "verification_test",
        "nested" => [
            "password" => "hunter2", // should be redacted
            "token" => "secret_token_123" // should be redacted
        ]
    ];

    echo "Sending fatal log...\n";
    $vt->log('fatal', 'SYSTEM CRITICAL FAILURE: Main power loss detected', $metadata);
    
    // Explicitly flush to see errors immediately
    $vt->dispose();
    
    echo "[PASS] Fatal log sent successfully!\n";

} catch (Throwable $e) {
    echo "[FAIL] " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), '401') !== false) {
        echo "Tip: Verify that the API Key in .env is valid and authorized.\n";
    }
}
