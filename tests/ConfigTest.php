<?php

namespace VedaTrace\Tests;

use PHPUnit\Framework\TestCase;
use VedaTrace\Config;

class ConfigTest extends TestCase
{
    public function testDefaultValuesApplied()
    {
        $config = new Config();

        $this->assertEquals('default-php-service', $config->service);
        $this->assertEquals('https://ingest.vedatrace.dev/v1/logs', $config->endpoint);
        $this->assertEquals('production', $config->environment);
        $this->assertEquals(100, $config->batchSize);
        $this->assertEquals(5000, $config->flushInterval);
        $this->assertEquals(3, $config->maxRetries);
        $this->assertEquals(1000, $config->retryDelay);
        $this->assertArrayHasKey('paths', $config->redaction);
        $this->assertEquals('[REDACTED]', $config->redaction['mask']);
    }

    public function testOptionsOverrideDefaults()
    {
        $config = new Config([
            'service' => 'my-custom-service',
            'apiKey' => 'test-key',
            'batchSize' => 50,
            'flushInterval' => 1000,
            'redaction' => [
                'paths' => ['user_id'],
                'mask' => '***'
            ]
        ]);

        $this->assertEquals('my-custom-service', $config->service);
        $this->assertEquals('test-key', $config->apiKey);
        $this->assertEquals(50, $config->batchSize);
        $this->assertEquals(1000, $config->flushInterval);
        $this->assertEquals(['user_id'], $config->redaction['paths']);
        $this->assertEquals('***', $config->redaction['mask']);
    }
}
