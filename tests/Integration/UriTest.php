<?php

namespace Tests\F3_PSR7\Integration;

use F3\Http\Uri;
use Psr\Http\Message\UriInterface;
use Http\Psr7Test\UriIntegrationTest;

class UriTest extends UriIntegrationTest
{
    public function createUri($uri): UriInterface
    {
        return new Uri($uri);
    }
}
