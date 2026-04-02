# VedaTrace PHP SDK

[![Latest Version](https://img.shields.io/github/release/Temples-Dev/vedatrace-php-sdk.svg?style=flat-square)](https://github.com/Temples-Dev/vedatrace-php-sdk/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

**VedaTrace** is a high-performance, PSR-3 compliant logging SDK for PHP. Built with reliability and scalability in mind, it provides seamless logging to the VedaTrace ingestion API with native support for Laravel, automatic data redaction, and intelligent batching.

---

## 🚀 Features

- **PSR-3 compliant**: Fully implements `Psr\Log\LoggerInterface`.
- **Laravel Ready**: First-class support via Service Providers, Facades, and Monolog handlers.
- **Intelligent Batching**: Buffers logs and flushes them in batches to optimize network overhead.
- **Data Redaction**: Protect sensitive information (passwords, tokens) with configurable JSON path-based masking.
- **Multiple Transports**: Choose between high-speed `HttpTransport` or local `ConsoleTransport` for development.
- **Child Loggers**: Create nested log contexts for request tracing and scoped metrics.

---

## 📦 Installation

Install VedaTrace via Composer:

```bash
composer require vedatrace/vedatrace-php
```

---

## ⚡ Quick Start

### Basic PHP Usage

The simplest way to start logging is using the `vedatrace()` global helper:

```php
use function VedaTrace\vedatrace;

$logger = vedatrace([
    'apiKey' => 'your-api-key',
    'service' => 'billing-api'
]);

$logger->info('Processing payment', [
    'order_id' => 12345,
    'amount' => 99.99,
    'card_number' => '4111********1111' // Redaction will handle sensitive info
]);
```

### Laravel Integration

VedaTrace integrates seamlessly with Laravel's logging stack.

**1. Service Provider & Facade**
The service provider is automatically discovered. You can use the `VedaTrace` facade immediately:

```php
use VedaTrace;

VedaTrace::error('Something went wrong in the application!');
```

**2. Monolog Channel**
Add VedaTrace to your `config/logging.php` to use it as a standard channel:

```php
'channels' => [
    'vedatrace' => [
        'driver' => 'monolog',
        'handler' => VedaTrace\Laravel\VedaTraceHandler::class,
        'level' => 'debug',
    ],
],
```

---

## 🛠️ Configuration

| Option | Env Variable | Default | Description |
|---|---|---|---|
| `apiKey` | `VEDATRACE_API_KEY` | `''` | Your VedaTrace API Key |
| `service` | `VEDATRACE_SERVICE` | `'default-php-service'` | Logical service name |
| `batchSize` | `VEDATRACE_BATCH_SIZE` | `100` | Logs per batch |
| `flushInterval` | `VEDATRACE_FLUSH_INTERVAL` | `5000` | Flush interval (ms) |
| `redaction` | N/A | `paths, mask` | Fields to mask |

---

## 📖 Documentation

For detailed guides and API reference, visit the **[docs/](docs/README.md)** directory:

- [Installation Guide](docs/installation.md)
- [Basic Usage](docs/basic-usage.md)
- [Framework Integrations](docs/framework-integrations.md)
- [Configuration Reference](docs/configuration.md)
- [API Reference](docs/api-reference.md)

---

## 🛡️ License

The VedaTrace PHP SDK is open-sourced software licensed under the [MIT license](LICENSE.md).

---

© 2024 VedaTrace Team. All rights reserved.
