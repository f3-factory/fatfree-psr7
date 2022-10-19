<?php

declare(strict_types=1);

namespace Tests\F3_PSR7\Factory;

use F3\Http\Factory\HTTPlug;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class HttplugFactoryTest extends TestCase
{
    public function testCreateRequest()
    {
        $factory = new HTTPlug();
        $r = $factory->createRequest('POST', 'https://fatfreeframework.com', ['Content-Type' => 'text/html'], 'foobar', '2.0');

        $this->assertEquals('POST', $r->getMethod());
        $this->assertEquals('https://fatfreeframework.com', $r->getUri()->__toString());
        $this->assertEquals('2.0', $r->getProtocolVersion());
        $this->assertEquals('foobar', $r->getBody()->__toString());

        $headers = $r->getHeaders();
        $this->assertCount(2, $headers); // Including HOST
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertEquals('text/html', $headers['Content-Type'][0]);
    }

    public function testCreateResponse()
    {
        $factory = new HTTPlug();
        $r = $factory->createResponse(217, 'Perfect', ['Content-Type' => 'text/html'], 'foobar', '2.0');

        $this->assertEquals(217, $r->getStatusCode());
        $this->assertEquals('Perfect', $r->getReasonPhrase());
        $this->assertEquals('2.0', $r->getProtocolVersion());
        $this->assertEquals('foobar', $r->getBody()->__toString());

        $headers = $r->getHeaders();
        $this->assertCount(1, $headers);
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertEquals('text/html', $headers['Content-Type'][0]);
    }

    public function testCreateStream()
    {
        $factory = new HTTPlug();
        $stream = $factory->createStream('foobar');

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals('foobar', $stream->__toString());
    }

    public function testCreateUri()
    {
        $factory = new HTTPlug();
        $uri = $factory->createUri('https://fatfreeframework.com/base');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals('https://fatfreeframework.com/base', $uri->__toString());
    }
}
