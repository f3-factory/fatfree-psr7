<?php

declare(strict_types=1);

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

require_once '../vendor/autoload.php';
require_once '../vendor/devanych/psr-http-benchmark/assets/CreatorFactory.php';
$packages = require_once 'packages.php';

if (empty($argv[1])) {
    throw new InvalidArgumentException('Specify the package for running.');
}

if (!isset($packages[$argv[1]])) {
    throw new InvalidArgumentException(sprintf(
        'Unsupported package `%s`; must be an any of the set (%s).',
        $argv[1],
        implode(', ', array_keys($packages))
    ));
}

$creator = new CreatorFactory();
$package = $packages[$argv[1]];

$runs = isset($argv[2]) ? (int) $argv[2] : 30000;
$start = microtime(true);

for ($i = 0; $i < $runs; $i++) {
    $creator->createRequest(new $package[RequestFactoryInterface::class]());
    $creator->createResponse(new $package[ResponseFactoryInterface::class]());
    $creator->createServerRequest(new $package[ServerRequestFactoryInterface::class]());
    $creator->createStream(new $package[StreamFactoryInterface::class]());
    $creator->createUploadedFile(
        new $package[UploadedFileFactoryInterface::class](),
        new $package[StreamFactoryInterface::class](),
    );
    $creator->createUri(new $package[UriFactoryInterface::class]());
}

$totalTime = microtime(true) - $start;

echo 'Runs: ' . number_format($runs) . PHP_EOL;
echo 'Runs per second: ' . floor($runs / $totalTime) . PHP_EOL;
echo 'Average time per run: ' . number_format(($totalTime / $runs) * 1000, 4) . ' ms' . PHP_EOL;
echo 'Total time: ' . number_format($totalTime, 4) . ' s' . PHP_EOL;
