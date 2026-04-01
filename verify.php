<?php

require_once __DIR__ . '/vendor/autoload.php';

use VedaTrace\Config;
use VedaTrace\Redactor;
use VedaTrace\VedaTrace;
use function VedaTrace\vedatrace;

function assert_equals($expected, $actual, $message) {
    if ($expected === $actual) {
        echo "✅ PASS: $message\n";
    } else {
        echo "❌ FAIL: $message\n";
        echo "   Expected: " . json_encode($expected) . "\n";
        echo "   Actual:   " . json_encode($actual) . "\n";
        exit(1);
    }
}

echo "Starting VedaTrace Verification...\n\n";

// 1. Test Config
echo "Testing Config Defaults...\n";
$config = new Config();
assert_equals('default-php-service', $config->service, 'Default service name');
assert_equals(100, $config->batchSize, 'Default batch size');

// 2. Test Redactor
echo "Testing Redactor...\n";
$redactor = new Redactor([
    'paths' => ['password', 'user.token'],
    'mask' => '[REDACTED]'
]);

$data = [
    'username' => 'alice',
    'password' => 'secret123',
    'user' => [
        'id' => 1,
        'token' => 'abc.def.ghi'
    ]
];

$redacted = $redactor->redact($data);
assert_equals('[REDACTED]', $redacted['password'], 'Redact top level password');
assert_equals('[REDACTED]', $redacted['user']['token'], 'Redact nested user.token');
assert_equals('alice', $redacted['username'], 'Keep username');

// 3. Test Factory
echo "Testing Factory and Helper...\n";
$logger = vedatrace(['apiKey' => 'test-key']);
if ($logger instanceof \VedaTrace\Logger) {
    echo "✅ PASS: Factory created Logger instance\n";
} else {
    echo "❌ FAIL: Factory failed to create Logger instance\n";
    exit(1);
}

// 4. Test Disposal
echo "Testing Disposal...\n";
$logger->info('Log before disposal');
$logger->dispose();
$logger->info('Log after disposal');
echo "✅ PASS: Logger dispose method called without failure\n";

echo "\nVerification Complete!\n";
