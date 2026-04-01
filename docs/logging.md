# Logging

The VedaTrace PHP SDK is fully PSR-3 compliant, allowing you to use standard logging methods.

## Log Levels

The following standard log levels are supported:

| Level | Method | Description |
|---|---|---|
| `DEBUG` | `$logger->debug()` | Use for detailed diagnostic information. |
| `INFO` | `$logger->info()` | Use for general application events. |
| `NOTICE` | `$logger->notice()` | Use for normal but significant events. |
| `WARNING` | `$logger->warning()`| Use for exceptional but non-error occurrences. |
| `ERR` | `$logger->error()` | Use for runtime errors that don't require immediate action. |
| `CRITICAL`| `$logger->critical()`| Use for critical conditions (application component unavailable). |
| `ALERT` | `$logger->alert()` | Use for actions that must be taken immediately. |
| `EMERG` | `$logger->emergency()`| Use for system is unusable. |
| `FATAL` | `$logger->fatal()` | Custom VedaTrace level for fatal application errors. |

## Structured Logging

VedaTrace emphasizes structured logging. Pass an associative array as the second argument to any logging method to include context:

```php
$logger->info('Processing order', [
  'order_id' => 123,
  'customer_id' => 'abc',
  'items' => 5
]);
```

All context data is serialized to JSON and sent to the VedaTrace API, allowing you to easily query and filter logs based on structured fields.
