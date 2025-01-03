# Fat-Free PSR-7 implementation

This is a lightweight [PSR-7](https://www.php-fig.org/psr/psr-7/) implementation for PHP 8.0+.

## Installation

```bash
composer require f3-factory/fatfree-psr7
```

## Usage

The package includes a [PSR-17](https://www.php-fig.org/psr/psr-17/) factory and also supports [HTTPlug](https://docs.php-http.org/en/latest/index.html).

Use these factories according to specs to create Request, Response, Uri, Stream objects.


## Tests

Run the test suite:

```bash
composer test
```

NB: Custom tests were mostly taken from [Nyholm/psr7](https://github.com/Nyholm/psr7). 


## Benchmark

Checkout `benchmark` branch, then run:

```
COMPOSER_ROOT_VERSION=1.0 composer update
```

Run benchmarks on php 8.2+:

```
cd benchmark/
php benchmark.php fatfree 50000
```

## Test results (best of 3 on MacBook M2 Pro)

| Runs: 50,000         | Guzzle    | HttpSoft  | Laminas   | Nyholm    | Slim      | Fatfree   |
|----------------------|-----------|-----------|-----------|-----------|-----------|-----------|
| Runs per second      | 14412     | 18608     | 17641     | 20549     | 14444     | 22233     |
| Average time per run | 0.0694 ms | 0.0537 ms | 0.0567 ms | 0.0487 ms | 0.0692 ms | 0.0450 ms |
| Total time           | 3.4691 s  | 2.6869 s  | 2.8342 s  | 2.4331 s  | 3.4616 s  | 2.2488 s  |

---
