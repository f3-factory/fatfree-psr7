<?php
declare(strict_types=1);

namespace F3\Http;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;


class UploadedFile implements UploadedFileInterface
{
    const E_INVALID_STATUS = 'Invalid upload file error status.';
    const E_UNABLE_TO_OPEN_FILE = 'Unable to open file "%s": %s';
    const E_UNABLE_TO_MOVE = 'Unable to move uploaded file to "%s": %s';
    const E_INVALID_MOVE_PATH = 'Invalid path for move operation. Cannot be empty.';
    const E_ALREADY_MOVED = 'Cannot retrieve stream after it was moved';
    const E_UPLOAD_ERROR = 'Cannot retrieve stream because of upload error';

    const UPLOAD_STATUS = [
        \UPLOAD_ERR_OK,
        \UPLOAD_ERR_INI_SIZE,
        \UPLOAD_ERR_FORM_SIZE,
        \UPLOAD_ERR_PARTIAL,
        \UPLOAD_ERR_NO_FILE,
        \UPLOAD_ERR_NO_TMP_DIR,
        \UPLOAD_ERR_CANT_WRITE,
        \UPLOAD_ERR_EXTENSION,
    ];

    private ?string $file = NULL;
    private bool $moved = false;
    private ?StreamInterface $stream = NULL;

    public function __construct(
        StreamInterface|string $streamOrFilepath,
        private int $size,
        private int $status,
        private ?string $clientFilename = null,
        private ?string $clientMediaType = null
    ) {
        if (!\in_array($status, self::UPLOAD_STATUS, true)) {
            throw new \InvalidArgumentException(self::E_INVALID_STATUS);
        }
        if ($this->status === \UPLOAD_ERR_OK) {
            if ($streamOrFilepath instanceof StreamInterface) {
                $this->stream = $streamOrFilepath;
            } else {
                $this->file = $streamOrFilepath;
            }
        }
    }

    protected function isAvailable(): void {
        if ($this->status !== \UPLOAD_ERR_OK) {
            throw new \RuntimeException(self::E_UPLOAD_ERROR);
        }
        if ($this->moved) {
            throw new \RuntimeException(self::E_ALREADY_MOVED);
        }
    }

    public function getStream(): StreamInterface {
        $this->isAvailable();
        if ($this->stream instanceof StreamInterface) {
            return $this->stream;
        }
        if (($resource = @\fopen($this->file, 'r')) === false) {
            throw new \RuntimeException(\sprintf(self::E_UNABLE_TO_OPEN_FILE, $this->file, \error_get_last()['message'] ?? ''));
        }
        return new Stream($resource);
    }

    public function moveTo($targetPath): void {
        $this->isAvailable();
        if (!\is_string($targetPath) || $targetPath === '') {
            throw new \InvalidArgumentException(self::E_INVALID_MOVE_PATH);
        }
        if ($this->file !== null) {
            $this->moved = 'cli' === PHP_SAPI
                ? @\rename($this->file, $targetPath)
                : @\move_uploaded_file($this->file, $targetPath);
            if ($this->moved === false) {
                throw new \RuntimeException(\sprintf(self::E_UNABLE_TO_MOVE, $targetPath, \error_get_last()['message'] ?? ''));
            }
        } else {
            $stream = $this->getStream();
            if ($stream->isSeekable()) {
                $stream->rewind();
            }
            if (($resource = @\fopen($targetPath, 'w')) === false) {
                throw new \RuntimeException(\sprintf(self::E_UNABLE_TO_OPEN_FILE, $targetPath, \error_get_last()['message'] ?? ''));
            }
            $dest = new Stream($resource);
            $stream->copyTo($dest);
            $this->moved = true;
        }
    }

    public function getSize(): int {
        return $this->size;
    }

    public function getError(): int {
        return $this->status;
    }

    public function getClientFilename(): ?string {
        return $this->clientFilename;
    }

    public function getClientMediaType(): ?string {
        return $this->clientMediaType;
    }
}
