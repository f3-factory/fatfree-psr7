<?php
declare(strict_types=1);

namespace F3\Http\Factory;

use F3\Http\Request;
use F3\Http\Response;
use F3\Http\Stream;
use F3\Http\Uri;
use Http\Message\{MessageFactory, StreamFactory, UriFactory};
use Psr\Http\Message\{RequestInterface, ResponseInterface, StreamInterface, UriInterface};

/**
 * HTTPlug PSR-17 Factory
 * @see https://github.com/php-http/httplug
 */
final class HTTPlug implements MessageFactory, StreamFactory, UriFactory
{
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1'): RequestInterface
    {
        return (new Request())
            ->withMethod($method)
            ->withUri($uri)
            ->withProtocolVersion($protocolVersion)
            ->withHeaders($headers)
            ->withBody(new Stream($body));
    }

    public function createResponse($statusCode = 200, $reasonPhrase = null, array $headers = [], $body = null, $protocolVersion = '1.1'): ResponseInterface
    {
        return (new Response())
            ->withStatus((int) $statusCode, $reasonPhrase)
            ->withHeaders($headers)
            ->withProtocolVersion($protocolVersion)
            ->withBody(new Stream($body));
    }

    public function createStream($body = null): StreamInterface
    {
        return new Stream($body ?? '');
    }

    public function createUri($uri = ''): UriInterface
    {
        return ($uri instanceof UriInterface) ? $uri : new Uri($uri);
    }
}
