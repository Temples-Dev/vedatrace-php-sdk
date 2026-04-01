# API Reference

The VedaTrace PHP SDK provides a lean set of classes for logging and configuration.

## VedaTrace\VedaTrace

Factory class for creating VedaTrace loggers.

| Method | Signature | Description |
|---|---|---|
| `create` | `create(array $config = []): Logger` | Create a new logger instance with the given configuration. |

## VedaTrace\Logger

The main logging class implementing `Psr\Log\LoggerInterface`.

| Method | Signature | Description |
|---|---|---|
| `log` | `log($level, $message, array $context = []): void` | Log a message at the given level. |
| `debug` | `debug($message, array $context = []): void` | Log a message at the debug level. |
| `info` | `info($message, array $context = []): void` | Log a message at the info level. |
| `warn` | `warn($message, array $context = []): void` | Log a message at the warn level. |
| `error` | `error($message, array $context = []): void` | Log a message at the error level. |
| `fatal`| `fatal($message, array $context = []): void` | Log a message at the fatal level. |
| `child`| `child(array $context): Logger` | Create a child logger with additional context. |
| `flush`| `flush(): void` | Manually flush any buffered log entries. |
| `dispose`| `dispose(): void` | Flush the buffer and disable the logger. |

## VedaTrace\Config

Handles configuration values and environment variables.

| Method | Signature | Description |
|---|---|---|
| `get` | `get(string $key, $default = null): mixed` | Get a configuration value by key. |

## Interoperability

The VedaTrace PHP SDK is built on standard PHP interfaces, including PSR-3 for logging and Guzzle for HTTP transport, ensuring high compatibility with modern PHP applications and frameworks.
