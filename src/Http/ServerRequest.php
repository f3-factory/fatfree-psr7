<?php

namespace F3\Http;

use Psr\Http\Message\{ServerRequestInterface,UploadedFileInterface};

class ServerRequest extends Request implements ServerRequestInterface {

    protected array $cookies = [];
    protected array $attributes = [];
    protected array $queryParams = [];
    protected array $serverParams = [];
    protected array|object|null $parsedBody = NULL;

    /** @var UploadedFileInterface[] */
    private array $uploadedFiles = [];

    public function getServerParams(): array {
        return $this->serverParams;
    }

    public function setServerParams(array $serverParams): void {
        $this->serverParams = $serverParams;
    }

    public function getCookieParams(): array {
        return $this->cookies;
    }

    public function withCookieParams(array $cookies): static {
        $new = clone $this;
        $new->cookies = $cookies;
        return $new;
    }

    public function getQueryParams(): array {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): static {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getUploadedFiles(): array {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): static {
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;
        return $new;
    }

    public function getParsedBody(): array|object|null {
        return $this->parsedBody;
    }

    public function withParsedBody($data): static {
        if (!\is_array($data) && !\is_object($data) && $data !== NULL)
            throw new \InvalidArgumentException('Invalid body data. Needs to be array, object or null.');
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }

    public function getAttributes(): array {
        return $this->attributes;
    }

    public function getAttribute($name,$default = NULL) {
        return \array_key_exists($name, $this->attributes)
            ? $this->attributes[$name] : $default;
    }

    public function withAttribute($name,$value): static {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }

    public function withoutAttribute($name): static {
        $new = clone $this;
        unset($new->attributes[$name]);
        return $new;
    }
}
