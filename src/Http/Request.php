<?php

namespace F3\Http;

use Psr\Http\Message\{RequestInterface,UriInterface};

class Request extends Message implements RequestInterface {

    protected ?UriInterface $uri = NULL;
    protected ?string $method=NULL;
    protected ?string $requestTarget=NULL;

    const VERBS = 'GET|HEAD|POST|PUT|PATCH|DELETE|CONNECT|OPTIONS|CUSTOM';

    public function getRequestTarget(): string {
        return $this->requestTarget !== NULL
            ? $this->requestTarget
            : (($p=$this->uri->getPath())===''?'/':$p).(($q=$this->uri->getQuery())!==''?'?'.$q:'');
    }

    public function withRequestTarget($requestTarget): static {
        if (\str_contains($requestTarget, ' ')) {
            throw new \InvalidArgumentException('Invalid request target provided; cannot contain whitespace');
        }
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function withMethod($method): static {
        if (!\is_string($method) || \stripos(self::VERBS, $method)===FALSE)
            throw new \InvalidArgumentException('Invalid method');
        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    public function getUri(): UriInterface {
        return $this->uri;
    }

    public function withUri(UriInterface|string $uri, $preserveHost = FALSE): static {
        if (\is_string($uri))
            $uri = new Uri($uri);
        if ($uri === $this->uri)
            return $this;
        $uriHost = $uri->getHost();
        if (!$preserveHost) {
            $new = empty($uriHost)
                ? clone $this
                : $this->withHeader('Host', $uriHost.(($uriPort = $uri->getPort())?':'.$uriPort:''));
        } elseif (!empty($uriHost) && empty($this->getHeader('Host'))) {
            $new = $this->withHeader('Host', $uriHost.(($uriPort = $uri->getPort())?':'.$uriPort:''));
        } else {
            $new = clone $this;
        }
        $new->uri = $uri;
        return $new;
    }

}

