<?php

namespace F3\Http;

use Psr\Http\Message\{MessageInterface,StreamInterface};

class Message implements MessageInterface {

    protected string $version = '1.1';
    protected array $headers = [];
    protected ?StreamInterface $body = NULL;

    public function getProtocolVersion(): string {
        return $this->version;
    }

    public function withProtocolVersion($version): static {
        if ($this->version === $version)
            return $this;
        $new = clone $this;
        $new->version = $version;
        return $new;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    protected function getHeaderName(string $name): ?string {
        $h = \preg_grep('/^'.$name.'$/i', \array_keys($this->headers));
        return $h ? \current($h) : null;
    }

    public function hasHeader($name): bool {
        if (!\is_string($name) || empty($name))
            throw new \InvalidArgumentException('Invalid header name');
        return $this->getHeaderName($name) !== NULL;
    }

    public function getHeader($name): array {
        return $this->headers[$this->getHeaderName($name)] ?? [];
    }

    public function getHeaderLine($name): string {
        if (!\is_string($name) || empty($name))
            throw new \InvalidArgumentException('Invalid header name');
        $oName = $this->getHeaderName($name);
        if (!$oName)
            return '';
        return \implode(', ', $this->headers[$oName]);
    }

    public function withHeader($name, $value): static {
        if (\is_int($value))
            $value = (string) $value;
        if (!\is_string($name) || empty($name))
            throw new \InvalidArgumentException('Invalid header name');
        if (!((\is_array($value) && !empty($value)) || \is_string($value)))
            throw new \InvalidArgumentException('Invalid header value');
        $headers = $this->headers;
        if (($oName = $this->getHeaderName($name)) !== null) {
            unset($headers[$oName]);
        }
        $headers[$name] = \is_array($value)
            ? \array_map(fn($v) => \trim((string) $v, " \t"),$value)
            : [\trim((string) $value, " \t")];
        $new = clone $this;
        $new->headers = $headers;
        return $new;
    }

    public function withHeaders(array $headers): static {
        $new = clone $this;
        foreach ($headers as $name => $value ) {
            $new = $new->withHeader($name, $value);
        }
        return $new;
    }

    public function withAddedHeader($name, $value): static {
        if (\is_int($value))
            $value = (string) $value;
        if (!\is_string($name) || empty($name))
            throw new \InvalidArgumentException('Invalid header name');
        if (!((\is_array($value) && !empty($value)) || \is_string($value)))
            throw new \InvalidArgumentException('Invalid header value');
        $headers = $this->headers;
        $oName = $this->getHeaderName($name);
        if (!$oName)
            $headers[$oName = $name] = [];
        foreach ((!\is_array($value)?[$value]:$value) as $v)
            $headers[$oName][] = \trim((string) $v, " \t");
        $new = clone $this;
        $new->headers = $headers;
        return $new;
    }

    public function withoutHeader($name): static {
        $headers = $this->headers;
        $oName = $this->getHeaderName($name);
        if ($oName === null)
            return $this;
        unset($headers[$oName]);
        $new = clone $this;
        $new->headers = $headers;
        return $new;
    }

    public function getBody(): StreamInterface {
        return $this->body;
    }

    public function withBody(StreamInterface $body): static {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }
}
