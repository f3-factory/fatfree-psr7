<?php

namespace F3\Http\Factory;

use F3\Http\{Request,Response,ServerRequest,Stream,UploadedFile,Uri};
use Psr\Http\Message\{
    RequestFactoryInterface,
    RequestInterface,
    ResponseFactoryInterface,
    ResponseInterface,
    ServerRequestFactoryInterface,
    ServerRequestInterface,
    StreamFactoryInterface,
    StreamInterface,
    UploadedFileFactoryInterface,
    UploadedFileInterface,
    UriFactoryInterface,
    UriInterface
};

/**
 * PSR-17 Factory
 * @see https://github.com/php-fig/http-factory
 */
class Psr17Factory implements RequestFactoryInterface, ResponseFactoryInterface, ServerRequestFactoryInterface, StreamFactoryInterface, UploadedFileFactoryInterface, UriFactoryInterface {

    public function createRequest(string $method, $uri): RequestInterface {
        return (new Request())->withMethod($method)->withUri($uri);
    }

    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface {
        return (new Response())->withStatus($code, $reasonPhrase);
    }

    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface {
        $new = (new ServerRequest())
            ->withUri($uri)
            ->withMethod($method);
        $new->setServerParams($serverParams);
        return $new;
    }

    public function createStream(string $content = ''): StreamInterface {
        return new Stream($content);
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface {
        if (!$filename || ($resource = @\fopen($filename, $mode)) === false) {
            throw new \RuntimeException('Unable to to open file');
        }
        return new Stream($resource);
    }

    public function createStreamFromResource($resource): StreamInterface {
        return new Stream($resource);
    }

    public function createUploadedFile(StreamInterface $stream, int $size = NULL, int $error = \UPLOAD_ERR_OK, string $clientFilename = NULL, string $clientMediaType = NULL): UploadedFileInterface {
        return new UploadedFile($stream, $size ?? $stream->getSize(), $error, $clientFilename, $clientMediaType);
    }

    public function createUri(string $uri = ''): UriInterface {
        return new Uri($uri);
    }
}
