# Development Mode

For local development, VedaTrace provides a built-in console transport that outputs logs directly to stdout. This allows you to verify your logs without having them sent to the VedaTrace API.

## Configuring Console Transport

To use development mode, set the `transport` configuration option to `console`:

```php
$logger = VedaTrace\VedaTrace::create([
    'transport' => 'console',
    'prettyPrint' => true // Set to true to print human-readable output
]);

$logger->info('Local log message');
```

## Pretty Printing

Pretty printing provides a formatted, easy-to-read layout for your logs on the terminal. By default, it uses simple text output. When `prettyPrint` is false, it outputs logs as raw JSON strings.

## Setting Through Environment Variables

You can also use environment variables to control development mode:

```bash
export VEDATRACE_TRANSPORT=console
export VEDATRACE_PRETTY_PRINT=true
```

The configuration logic will automatically pick up these values if they are not explicitly set in the initialization array.
