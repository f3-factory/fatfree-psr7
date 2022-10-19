<?php

namespace Tests\F3_PSR7\Integration;

use F3\Http\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Http\Psr7Test\ServerRequestIntegrationTest;

class ServerRequestTest extends ServerRequestIntegrationTest
{
    public function createSubject(): ServerRequestInterface
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $sr = (new ServerRequest())->withMethod('GET')->withUri('/');
        $sr->setServerParams($_SERVER);
        return $sr;
    }
}
