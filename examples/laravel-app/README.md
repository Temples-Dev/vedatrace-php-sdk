# Laravel Integration Example

This directory contains a minimal Laravel integration example for the VedaTrace PHP SDK.

## Features Demonstrated

- **Service Provider**: Automatic singleton registration of the `Logger`.
- **Facades**: Clean, static access via `VedaTrace::info()`.
- **Monolog Handler**: Seamless integration with Laravel's logging stack via `VedaTraceHandler`.
- **Configuration**: Standard Laravel config file (`config/vedatrace.php`).

## Setup

### 1. Install Dependencies
Run composer install. This will use the local path repository to include the VedaTrace SDK from the parent directory.

```bash
composer install
```

### 2. Configure Environment
Add your API key to your `.env` file:

```env
VEDATRACE_API_KEY=your-api-key-here
VEDATRACE_SERVICE=my-laravel-app
```

### 3. Usage
You can now start logging from anywhere in your Laravel application.

**Via Facade:**
```php
use VedaTrace\Laravel\Facades\VedaTrace;

VedaTrace::info('Sent from Laravel!');
```

**Via Logging Stack:**
```php
Log::channel('vedatrace')->info('Sent through Monolog handler!');
```

## Exploring the Example
- **`config/vedatrace.php`**: Standard configuration settings.
- **`config/logging.php`**: Shows how to add the `vedatrace` channel.
- **`routes/web.php`**: Example route usage.
