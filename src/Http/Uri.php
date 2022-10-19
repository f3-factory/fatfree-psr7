<?php
declare(strict_types=1);

namespace F3\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface {

    protected string $scheme = '';
    protected string $host = '';
    protected ?int $port = null;
    protected string $user = '';
    protected string $pass = '';
    protected string $query = '';
    protected string $path = '';
    protected string $fragment = '';

    public function __construct(?string $uri = NULL) {
        if ($uri !== NULL) {
            $parts = \parse_url($uri);
            if (!\is_array($parts))
                throw new \InvalidArgumentException('Unable to parse URI: "'.$uri.'"');
            foreach ($parts as $key => $value) {
                $this->{$key} = match ($key) {
                    'host', 'scheme' => \strtolower($value),
                    'query', 'fragment' => $this->encode($value),
                    'path' => $this->encode($value, true),
                    default => $value,
                };
            }
        }
    }

    protected function encode(string $string, $isPath=false): ?string {
        return \preg_replace_callback('/(?!%[A-Fa-f\d]{2})[^\/:'.($isPath?'':'?').'\[\]@!$&\'()*+,;=\w\d\-.~]+/',
            static fn ($match) => \rawurlencode($match[0]), $string);
    }

    public function getScheme(): string {
        return $this->scheme;
    }

    public function getAuthority(): string {
        if (($host = $this->getHost()) === '')
            return '';
        return ($this->user!==''?$this->user.($this->pass!==''?':'.$this->pass:'').'@':'').
            $host.(($port=$this->getPort())?(':'.$port):'');
    }

    public function getUserInfo(): string {
        return $this->user.($this->pass!==''?':'.$this->pass:'');
    }

    public function getHost(): string {
        return $this->host;
    }

    public function getPort(): ?int {
        return match ($this->scheme) {
            'http' => 80,
            'https' => 443,
            default => null,
        } === $this->port ? null : $this->port;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getQuery(): string {
        return $this->query;
    }

    public function getFragment(): string {
        return $this->fragment;
    }

    public function withScheme($scheme): Uri {
        if (!\is_string($scheme))
            throw new \InvalidArgumentException('Scheme must be a string');
        $new = clone $this;
        $new->scheme = \strtolower($scheme);
        return $new;
    }

    public function withUserInfo($user, $password = NULL): Uri {
        $new = clone $this;
        $new->user = $user;
        $new->pass = (string) $password;
        return $new;
    }

    public function withHost($host): Uri {
        if (!\is_string($host))
            throw new \InvalidArgumentException('Host must be a string');
        $new = clone $this;
        $new->host = \strtolower($host);
        return $new;
    }

    public function withPort($port): Uri {
        if ($port !== NULL && $port < 0 || $port > 65535)
            throw new \InvalidArgumentException(sprintf('Invalid port: %d. Must be between 0 and 65535',$port));
        $new = clone $this;
        $new->port = $port !== NULL ? (int) $port : NULL;
        return $new;
    }

    public function withPath($path): Uri {
        if (!\is_string($path))
            throw new \InvalidArgumentException('Path must be a string');
        $new = clone $this;
        $new->path = $this->encode($path, true);
        return $new;
    }

    public function withQuery($query): Uri {
        if (!\is_string($query))
            throw new \InvalidArgumentException('Query and fragment must be a string');
        $new = clone $this;
        $new->query = $this->encode($query);
        return $new;
    }

    public function withFragment($fragment): Uri {
        if (!\is_string($fragment))
            throw new \InvalidArgumentException('Query and fragment must be a string');
        $new = clone $this;
        $new->fragment = $this->encode($fragment);
        return $new;
    }

    public function __toString() {
        return (($s=$this->getScheme()) !== '' ? $s.':' : '').
            (($a=$this->getAuthority()) !== '' ? '//'.$a : '').
            (($p=$this->getPath()) !=='' ? (
                (!($abs=\str_starts_with($p, '/')) && $a !== '' ? '/' : '').
                ($abs && $a === '' ? '/'.\ltrim($p, '/') : $p)
            ) : '').
            (($q=$this->getQuery())!==''?'?'.$q:'').
            (($f=$this->getFragment())!==''?'#'.$f:'');
    }
}
