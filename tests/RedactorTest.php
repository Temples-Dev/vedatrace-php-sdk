<?php

namespace VedaTrace\Tests;

use PHPUnit\Framework\TestCase;
use VedaTrace\Redactor;

class RedactorTest extends TestCase
{
    public function testRedactsSpecifiedPaths()
    {
        $redactor = new Redactor([
            'paths' => ['password', 'user.token'],
            'mask' => '[REDACTED]'
        ]);

        $data = [
            'username' => 'alice',
            'password' => 'secret123',
            'user' => [
                'id' => 1,
                'token' => 'abc.def.ghi'
            ]
        ];

        $redacted = $redactor->redact($data);

        $this->assertEquals('[REDACTED]', $redacted['password']);
        $this->assertEquals('[REDACTED]', $redacted['user']['token']);
        $this->assertEquals('alice', $redacted['username']);
        $this->assertEquals(1, $redacted['user']['id']);
    }

    public function testDoesNothingIfPathsEmpty()
    {
        $redactor = new Redactor(['paths' => []]);
        $data = ['foo' => 'bar'];
        $this->assertEquals($data, $redactor->redact($data));
    }
}
