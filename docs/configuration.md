# Configuration

Configure VedaTrace to suit your application's environment. You can pass options directly or use environment variables.

## Configuration Options

| Option | Environment Variable | Default | Description |
|---|---|---|---|
| `apiKey` | `VEDATRACE_API_KEY` | `''` | Your VedaTrace API key. |
| `service` | `VEDATRACE_SERVICE` | `'default-php-service'` | Name of your service as it appears in VedaTrace. |
| `environment`| `VEDATRACE_ENVIRONMENT` | `'production'` | The environment (local, staging, etc.). |
| `endpoint` | `VEDATRACE_ENDPOINT` | `https://ingest.vedatrace.dev/v1/logs` | The VedaTrace ingestion endpoint. |
| `batchSize` | `VEDATRACE_BATCH_SIZE` | `100` | Maximum number of logs per batch. |
| `flushInterval`| `VEDATRACE_FLUSH_INTERVAL`| `5000` | Time between batches (milliseconds). |
| `maxRetries` | `VEDATRACE_MAX_RETRIES` | `3` | Number of retry attempts for failed requests. |
| `retryDelay` | `VEDATRACE_RETRY_DELAY` | `1000` | Delay between retries (milliseconds). |

## Redaction

VedaTrace automatically redacts sensitive data from your logs. You can customize the fields to mask:

```php
$logger = vedaTrace([
    'redaction' => [
        'paths' => ['user.token', 'credit_card.number', 'password'],
        'mask' => '[REDACTED]'
    ]
]);

$logger->info('User login', [
    'user' => ['token' => '123456'], // Token will be masked
    'password' => 'secret' // Password will be masked
]);
```

By default, the following keys are redacted:
- `password`
- `token`
- `secret`
- `authorization`

## Batching and Performance

VedaTrace buffers logs in memory and sends them in batches to improve application performance.

- **`batchSize`**: Limits the number of logs sent in a single request.
- **`flushInterval`**: Determines how often logs are flushed if the batch size is not reached.
- **`dispose()`**: In standalone scripts, ensure you call `$logger->dispose()` to flush any remaining logs before the script exits.
