# Installation

To install the VedaTrace PHP SDK, use Composer:

```bash
composer require vedatrace/vedatrace-php
```

## Requirements

- PHP 8.2 or higher
- PSR-3 Log implementation (included)
- Guzzle HTTP Client (included)

## Post-Installation

The SDK comes with a global helper function `vedatrace()` which is automatically loaded by Composer's autoloader. To ensure it's available, make sure you've included the Composer autoloader in your project:

```php
require_once 'vendor/autoload.php';
```
