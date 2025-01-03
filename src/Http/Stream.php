<?php

namespace F3\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface {

    protected mixed $resource;
    protected ?bool $seekable = NULL;
    protected ?bool $readable = NULL;
    protected ?bool $writable = NULL;
    protected mixed $uri = null;
    private ?int $size=NULL;

    public function __construct(mixed $data) {
        if ($data === null)
            $data = '';
        if (\is_string($data)) {
            $resource = \fopen('php://temp', 'r+');
            \fwrite($resource, $data);
            $data = $resource;
        }
        if (!\is_resource($data))
            throw new \InvalidArgumentException('Invalid body or file provided for stream');
        $this->resource = $data;
    }

    public function __toString(): string {
        if ($this->isSeekable())
            $this->seek(0);
        return $this->getContents();
    }

    public function close(): void {
        if ($this->resource) {
            if (\is_resource($this->resource))
                \fclose($this->resource);
            $this->detach();
        }
    }

    public function detach(): mixed {
        if (!$this->resource)
            return null;
        $result = $this->resource;
        unset($this->resource);
        $this->resource = NULL;
        $this->size = $this->uri = null;
        $this->readable = $this->writable = $this->seekable = NULL;
        return $result;
    }

    public function getSize(): ?int {
        if ($this->size || !$this->resource)
            return $this->size;
        $stats = \fstat($this->resource) ?? [];
        return $this->size = $stats['size'] ?? NULL;
    }

    protected function getUri() {
        if ($this->uri !== FALSE) {
            $this->uri = $this->getMetadata('uri') ?? FALSE;
        }
        return $this->uri;
    }

    public function tell(): int {
        if (!$this->resource)
            throw new \RuntimeException('No stream available');
        if (($result = @\ftell($this->resource)) === false)
            throw new \RuntimeException('Unable to tell stream position: '.(error_get_last()['message'] ?? ''));
        return $result;
    }

    public function eof(): bool {
        return !$this->resource || \feof($this->resource);
    }

    public function isSeekable(): bool {
        if ($this->seekable === NULL) {
            $this->seekable = $this->getMetadata('seekable')
                && \fseek($this->resource, 0, SEEK_CUR) === 0;
        }
        return $this->seekable;
    }

    public function seek($offset, $whence = SEEK_SET): void {
        if (!$this->resource || !$this->isSeekable())
            throw new \RuntimeException('Cannot access stream');
        if (\fseek($this->resource, $offset, $whence) === -1)
            throw new \RuntimeException(\sprintf('Cannot seek to stream position "%d" with whence %s', $offset, \var_export($whence,true)));
    }

    public function rewind(): void {
        $this->seek(0);
    }

    public function isWritable(): bool {
        if ($this->writable === NULL) {
            $this->writable = ($mode=$this->getMetadata('mode'))
                && (\str_contains($mode,'w') || \str_contains($mode,'+') || \str_contains($mode,'x') || \str_contains($mode,'c') || \str_contains($mode,'a'));
        }
        return $this->writable;
    }

    public function write($string): int {
        if (!$this->resource || !$this->isWritable())
            throw new \RuntimeException('Cannot write to stream');
        if (($result = @\fwrite($this->resource, $string)) === FALSE)
            throw new \RuntimeException('Cannot write to stream: '.(\error_get_last()['message'] ?? ''));
        $this->size = null;
        return $result;
    }

    public function isReadable(): bool {
        if ($this->readable === NULL) {
            $this->readable = ($mode=$this->getMetadata('mode'))
                && (\str_contains($mode,'r') || \str_contains($mode,'+'));
        }
        return $this->readable;
    }

    public function read($length): string {
        if (!$this->resource || !$this->isReadable())
            throw new \RuntimeException('Cannot read from stream');
        if (($result = @\fread($this->resource, $length)) === FALSE)
            throw new \RuntimeException('Cannot read from stream: '.(\error_get_last()['message'] ?? ''));
        return $result;
    }

    public function getContents(): string {
        if (!$this->resource)
            throw new \RuntimeException('No stream available');
        if (($contents = @\stream_get_contents($this->resource)) === FALSE)
            throw new \RuntimeException('Cannot read stream contents: '.(\error_get_last()['message'] ?? ''));
        return $contents;
    }

    public function getMetadata($key = NULL) {
        if (!$this->resource)
            return $key ? null : [];
        $meta = \stream_get_meta_data($this->resource);
        return !$key ? $meta : ($meta[$key] ?? null);
    }

    public function copyTo(Stream $dest) {
        \stream_copy_to_stream($this->resource, $dest->resource);
    }

    public function __destruct() {
        $this->close();
    }
}
