# Advanced Usage

VedaTrace provides advanced features to precisely control how and when your logs are sent.

## Manual Flushing

You can manually trigger the flushing of buffered logs to the VedaTrace API using the `flush()` method:

```php
$logger->flush();
```

Use `flush()` when your application is about to exit or to ensure a critical set of logs is sent immediately.

## Graceful Shutdown

For long-running processes (like worker loops or CLI commands), it is recommended to explicitly dispose the logger when the process is finished. This ensures all buffered logs are sent and prevents any further logging:

```php
$logger->dispose();
```

After calling `dispose()`, any subsequent calls to `log()`, `info()`, etc., will be silently ignored.

## Custom Transports

You can implement your own transport by extending the `VedaTrace\Transports\TransportInterface`:

```php
use VedaTrace\Transports\TransportInterface;

class MyCustomTransport implements TransportInterface {
    public function send(array $entries): void {
        // Custom logic to send log entries
    }
}

$logger = VedaTrace\VedaTrace::create([
    'transport' => new MyCustomTransport()
]);
```

## Retry and Exception Handling

The HTTP transport includes built-in retry logic to recover from transient network errors. All errors are logged to `error_log` by default. You can also handle transport exceptions in your own application code if needed.

## Manual Batch Management

If your application handles large volumes of logs, you can adjust the `batchSize` and `flushInterval` to match your infrastructure's capacity. These values can be tuned to balance performance with low-latency log visibility.
- **Large Batch Size**: High performance, lower network overhead.
- **Short Flush Interval**: Higher real-time visibility, potentially more small requests.
- **Manual Flush**: Useful for periodic background tasks.
