# Basic Usage

To get started with VedaTrace, you can use the `vedatrace()` global helper function or create a logger instance using the `VedaTrace::create()` factory method.

## Initializing the Logger

Pass an array of configuration options to the initialization method:

```php
use VedaTrace\VedaTrace;

$logger = VedaTrace::create([
    'apiKey' => 'your-api-key',
    'service' => 'my-app-name',
]);
```

## Global Helper

The `vedatrace()` helper function is a convenient shorthand for initialization:

```php
use function VedaTrace\vedatrace;

$logger = vedatrace(['apiKey' => 'your-api-key']);
$logger->info('Hello, VedaTrace!');
```

## Logging Messages

The logger implementes the `Psr\Log\LoggerInterface`, providing standard logging methods:

```php
$logger->debug('Debugging message');
$logger->info('Informational message');
$logger->warn('Warning message');
$logger->error('Error message');
$logger->fatal('Fatal error message');
```

## Context Data

You can pass an optional associative array as context for any log message. This data will be automatically nested under a `metadata` field in VedaTrace:

```php
$logger->info('User login successful', [
    'user_id' => 123,
    'ip_address' => '127.0.0.1'
]);
```

## Disposing the Logger

VedaTrace batches logs to optimize performance. In long-running processes (like Laravel), the SDK handles flushing automatically. However, in **standalone CLI scripts**, you must call `dispose()` to ensure all buffered logs are sent before the script exits:

```php
$logger->info('Script finished');
$logger->dispose(); // Flush and close
```
