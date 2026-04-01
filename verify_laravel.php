<?php

require_once __DIR__ . '/vendor/autoload.php';

use VedaTrace\Laravel\VedaTraceServiceProvider;
use VedaTrace\Logger;
use Illuminate\Container\Container;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Support\Facades\Facade;

echo "Starting Laravel Integration Verification...\n\n";

use Illuminate\Contracts\Foundation\Application;

class MockContainer extends Container implements Application {
    public function version() { return '11.0.0'; }
    public function basePath($path = '') { return '/tmp/' . $path; }
    public function bootstrapPath($path = '') { return ''; }
    public function configPath($path = '') { return '/tmp/config/' . $path; }
    public function databasePath($path = '') { return ''; }
    public function langPath($path = '') { return ''; }
    public function publicPath($path = '') { return ''; }
    public function resourcePath($path = '') { return ''; }
    public function storagePath($path = '') { return ''; }
    public function environment(...$environments) { return 'testing'; }
    public function runningInConsole() { return true; }
    public function runningUnitTests() { return false; }
    public function hasDebugModeEnabled() { return true; }
    public function maintenanceMode() { return null; }
    public function isDownForMaintenance() { return false; }
    public function registerConfiguredProviders() {}
    public function register($provider, $force = false) {}
    public function registerDeferredProvider($provider, $service = null) {}
    public function resolveProvider($provider) {}
    public function boot() {}
    public function booting($callback) {}
    public function booted($callback) {}
    public function bootstrapWith(array $bootstrappers) {}
    public function getLocale() { return 'en'; }
    public function getNamespace() { return 'App\\'; }
    public function getProviders($provider) { return []; }
    public function hasBeenBootstrapped() { return true; }
    public function loadDeferredProviders() {}
    public function setLocale($locale) {}
    public function shouldSkipMiddleware() { return false; }
    public function terminating($callback) {}
    public function terminate() {}
}

if (!function_exists('config_path')) {
    function config_path($path = '') { return '/tmp/config/' . $path; }
}

$app = new MockContainer();
$app->instance('config', new ConfigRepository([
    'vedatrace' => [
        'apiKey' => 'laravel-test-key',
        'service' => 'laravel-test-service'
    ]
]));
Facade::setFacadeApplication($app);

// 2. Register Service Provider
$provider = new VedaTraceServiceProvider($app);
$provider->register();

// 3. Test Facade / Container Resolve
$logger = $app->make('vedatrace');

if ($logger instanceof Logger) {
    echo "✅ PASS: VedaTrace resolved from container\n";
    if ($logger->child(['test' => true]) instanceof Logger) {
        echo "✅ PASS: Logger methods accessible\n";
    }
} else {
    echo "❌ FAIL: Failed to resolve VedaTrace from container\n";
    exit(1);
}

// 4. Test Facade
try {
    \VedaTrace\Laravel\Facades\VedaTrace::info('Test log via Facade');
    echo "✅ PASS: Facade method call successful (sent to console by default)\n";
} catch (\Exception $e) {
    echo "❌ FAIL: Facade method call failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nLaravel Integration Verified!\n";
