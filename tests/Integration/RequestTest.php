<?php

namespace Tests\F3_PSR7\Integration;

use F3\Http\Request;
use Psr\Http\Message\RequestInterface;
use Http\Psr7Test\RequestIntegrationTest;

class RequestTest extends RequestIntegrationTest
{
    public function createSubject(): RequestInterface
    {
        return (new Request())->withMethod('GET')->withUri('/');
    }
}
