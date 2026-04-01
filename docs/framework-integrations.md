# Framework Integrations

VedaTrace provides "plug-and-play" support for the Laravel framework through a dedicated Service Provider and Facade.

## Laravel Integration

The SDK's Laravel components are provided out-of-the-box:

### Service Provider

To register VedaTrace in Laravel, add the `VedaTraceServiceProvider` to your `config/app.php` providers if not automatically discovered:

```php
'providers' => [
    // ...
    VedaTrace\Laravel\VedaTraceServiceProvider::class,
],
```

### Facade

The `VedaTrace` facade is available for static access in any Laravel class:

```php
use VedaTrace\Laravel\Facades\VedaTrace;

VedaTrace::info('Logged via Facade!');
```

### Configuration

Publish the default configuration file to your project:

```bash
php artisan vendor:publish --tag=vedatrace-config
```

Custom configurations can be set in `config/vedatrace.php`.

## Monolog Handler

VedaTrace also provides a custom Monolog handler (`VedaTraceHandler`). You can register it in your `config/logging.php` to include VedaTrace in your app's standard logging stack:

```php
'channels' => [
    // ...
    'vedatrace' => [
        'driver' => 'monolog',
        'handler' => VedaTrace\Laravel\VedaTraceHandler::class,
        'level' => 'debug',
    ],
],
```

Logging to the `vedatrace` channel will automatically send logs to the VedaTrace API through the SDK's internal logging logic.
