# Configuration

Configure VedaTrace to suit your application's environment. You can pass options directly or use environment variables.

## Configuration Options

| Option | Environment Variable | Default | Description |
|---|---|---|---|
| `apiKey` | `VEDATRACE_API_KEY` | `''` | Your VedaTrace API key. |
| `service` | `VEDATRACE_SERVICE` | `'php-app'` | The name of your service. |
| `batchSize` | `VEDATRACE_BATCH_SIZE` | `5` | Maximum number of logs per batch. |
| `flushInterval` | `VEDATRACE_FLUSH_INTERVAL`| `10` | Time interval between batches (seconds). |
| `redactPaths` | `VEDATRACE_REDACT_PATHS` | `['password', '...']` | JSON paths to redact from context. |
| `transport` | `VEDATRACE_TRANSPORT` | `'http'` | `http` or `console`. |
| `prettyPrint`| `VEDATRACE_PRETTY_PRINT` | `false` | For use with `console` transport. |

## Redaction

VedaTrace automatically redacts sensitive data from your logs. You can customize the fields to mask:

```php
$logger = vedaTrace([
    'redactPaths' => ['user.token', 'credit_card.number', 'password']
]);

$logger->info('User login', [
    'user' => ['token' => '123456'], // Token will be redacted
    'password' => 'secret' // Password will be redacted
]);
```

## Batching and Performance

VedaTrace buffers logs in memory and sends them in batches to improve application performance. You can adjust the batch size and flush interval to balance real-time visibility with overhead.
- **`batchSize`**: Limits the number of logs sent in a single request.
- **`flushInterval`**: Determines how often logs are flushed if the batch size is not reached.
