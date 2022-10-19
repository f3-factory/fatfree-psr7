<?php

namespace Tests\F3_PSR7\Integration;

use F3\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Http\Psr7Test\ResponseIntegrationTest;

class ResponseTest extends ResponseIntegrationTest
{
    public function createSubject(): ResponseInterface
    {
        return new Response();
    }
}
