<?php

namespace VedaTrace\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VedaTrace\Logger
 * 
 * @method static void debug(string|\Stringable $message, array $context = [])
 * @method static void info(string|\Stringable $message, array $context = [])
 * @method static void warning(string|\Stringable $message, array $context = [])
 * @method static void error(string|\Stringable $message, array $context = [])
 * @method static void fatal(string|\Stringable $message, array $context = [])
 * @method static \VedaTrace\Logger child(array $metadata)
 * @method static void flush()
 * @method static void close()
 */
class VedaTrace extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'vedatrace';
    }
}
