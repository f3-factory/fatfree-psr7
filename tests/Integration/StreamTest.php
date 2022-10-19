<?php

namespace Tests\F3_PSR7\Integration;

use F3\Http\Stream;
use Psr\Http\Message\StreamInterface;
use Http\Psr7Test\StreamIntegrationTest;

class StreamTest extends StreamIntegrationTest
{
    public function createStream($data): StreamInterface
    {
        return new Stream($data);
    }
}
