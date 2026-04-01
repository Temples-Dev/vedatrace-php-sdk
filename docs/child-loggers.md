# Child Loggers

Child loggers allow you to create nested log contexts for specific parts of your application. This is useful for scoped metadata, such as adding session or request IDs to all messages in a particular execution path.

## Creating a Child Logger

To create a child logger, use the `child()` method on any logger instance:

```php
$requestLogger = $logger->child([
  'request_id' => 'abc-123',
  'user_id' => 456
]);

$requestLogger->info('Request started');
// Log message will include { "request_id": "abc-123", "user_id": 456 }
```

## Scoped Metrics

Child loggers effectively scope all log messages emitted within that scope. This is invaluable for tracing requests across multiple services and components.

## Inheriting Context

Child loggers inherit the context of their parent. If you add additional context to a child, it will merge with the parent's context for all future messages sent through the child:

```php
$parent = $logger->child(['app' => 'crm']);
$child = $parent->child(['component' => 'billing']);

$child->info('Payment successful');
// Log context: { "app": "crm", "component": "billing" }
```
