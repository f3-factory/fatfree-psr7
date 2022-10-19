<?php

namespace Tests\F3_PSR7\Integration;

use F3\Http\Factory\Psr17Factory;
use F3\Http\Stream;
use Psr\Http\Message\UploadedFileInterface;
use Http\Psr7Test\UploadedFileIntegrationTest;

class UploadedFileTest extends UploadedFileIntegrationTest
{
    public function createSubject(): UploadedFileInterface
    {
        return (new Psr17Factory())->createUploadedFile(new Stream('writing to tempfile'));
    }
}
