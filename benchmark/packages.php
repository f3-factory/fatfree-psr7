<?php

declare(strict_types=1);

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

require_once '../vendor/devanych/psr-http-benchmark/assets/GuzzleFactory.php';

return [
    'guzzle' => [
        RequestFactoryInterface::class => GuzzleFactory::class,
        ResponseFactoryInterface::class => GuzzleFactory::class,
        ServerRequestFactoryInterface::class => GuzzleFactory::class,
        StreamFactoryInterface::class => GuzzleFactory::class,
        UploadedFileFactoryInterface::class => GuzzleFactory::class,
        UriFactoryInterface::class => GuzzleFactory::class,
    ],
    'httpsoft' => [
        RequestFactoryInterface::class => HttpSoft\Message\RequestFactory::class,
        ResponseFactoryInterface::class => HttpSoft\Message\ResponseFactory::class,
        ServerRequestFactoryInterface::class => HttpSoft\Message\ServerRequestFactory::class,
        StreamFactoryInterface::class => HttpSoft\Message\StreamFactory::class,
        UploadedFileFactoryInterface::class => HttpSoft\Message\UploadedFileFactory::class,
        UriFactoryInterface::class => HttpSoft\Message\UriFactory::class,
    ],
    'laminas' => [
        RequestFactoryInterface::class => Laminas\Diactoros\RequestFactory::class,
        ResponseFactoryInterface::class => Laminas\Diactoros\ResponseFactory::class,
        ServerRequestFactoryInterface::class => Laminas\Diactoros\ServerRequestFactory::class,
        StreamFactoryInterface::class => Laminas\Diactoros\StreamFactory::class,
        UploadedFileFactoryInterface::class => Laminas\Diactoros\UploadedFileFactory::class,
        UriFactoryInterface::class => Laminas\Diactoros\UriFactory::class,
    ],
    'nyholm' => [
        RequestFactoryInterface::class => Nyholm\Psr7\Factory\Psr17Factory::class,
        ResponseFactoryInterface::class => Nyholm\Psr7\Factory\Psr17Factory::class,
        ServerRequestFactoryInterface::class => Nyholm\Psr7\Factory\Psr17Factory::class,
        StreamFactoryInterface::class => Nyholm\Psr7\Factory\Psr17Factory::class,
        UploadedFileFactoryInterface::class => Nyholm\Psr7\Factory\Psr17Factory::class,
        UriFactoryInterface::class => Nyholm\Psr7\Factory\Psr17Factory::class,
    ],
    'slim' => [
        RequestFactoryInterface::class => Slim\Psr7\Factory\RequestFactory::class,
        ResponseFactoryInterface::class => Slim\Psr7\Factory\ResponseFactory::class,
        ServerRequestFactoryInterface::class => Slim\Psr7\Factory\ServerRequestFactory::class,
        StreamFactoryInterface::class => Slim\Psr7\Factory\StreamFactory::class,
        UploadedFileFactoryInterface::class => Slim\Psr7\Factory\UploadedFileFactory::class,
        UriFactoryInterface::class => Slim\Psr7\Factory\UriFactory::class,
    ],
    'fatfree' => [
        RequestFactoryInterface::class => F3\Http\Factory\Psr17Factory::class,
        ResponseFactoryInterface::class => F3\Http\Factory\Psr17Factory::class,
        ServerRequestFactoryInterface::class => F3\Http\Factory\Psr17Factory::class,
        StreamFactoryInterface::class => F3\Http\Factory\Psr17Factory::class,
        UploadedFileFactoryInterface::class => F3\Http\Factory\Psr17Factory::class,
        UriFactoryInterface::class => F3\Http\Factory\Psr17Factory::class,
    ],
];
