# Framework Integrations

VedaTrace provides "plug-and-play" support for the Laravel framework through a dedicated Service Provider and Facade.

## Laravel Integration

The SDK's Laravel components are provided out-of-the-box and support Laravel 10, 11, and 12+.

### 1. Installation

Ensure the package is installed via Composer (if not already):

```bash
composer require vedatrace/vedatrace-php
```

### 2. Service Provider Registration

For **Laravel 11 and 12+**, add the provider to `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    VedaTrace\Laravel\VedaTraceServiceProvider::class, // Add this
];
```

For **Laravel 10**, add it to the `providers` array in `config/app.php`:

```php
'providers' => [
    // ...
    VedaTrace\Laravel\VedaTraceServiceProvider::class,
],
```

### 3. Environment Configuration

Add your VedaTrace API key and service name to your `.env` file:

```env
VEDATRACE_API_KEY=your_api_key_here
VEDATRACE_SERVICE=my-laravel-app
LOG_CHANNEL=vedatrace
```

### 4. Logging Configuration

Add the `vedatrace` channel to your `config/logging.php` file:

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

### 5. Usage

Once configured, you can use the standard Laravel `Log` facade:

```php
use Illuminate\Support\Facades\Log;

Log::info('User login attempt', ['user_id' => 456]);
```

Or use the `VedaTrace` facade directly for more control:

```php
use VedaTrace\Laravel\Facades\VedaTrace;

VedaTrace::error('Database connection failed', [
    'host' => 'db.internal',
    'retry_attempt' => 3
]);
```

### 6. Redaction

VedaTrace automatically redacts sensitive information. By default, keys like `password`, `token`, and `secret` are masked. You can customize this by publishing the config:

```bash
php artisan vendor:publish --tag=vedatrace-config
```

Then edit `config/vedatrace.php`.

> [!TIP]
> All contextual data passed to log methods (e.g., `['user_id' => 123]`) is automatically nested under a `metadata` object in the VedaTrace dashboard to keep your logs structured and easy to search.
