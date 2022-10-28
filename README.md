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


## Benchmarks

Benchmarks done with `devanych/psr-http-benchmark` on php 8.0+.

Test results (Intel Xeon Gold 6140 CPU @ 2.30GHz, 4 cores):

| Runs: 50,000         | Guzzle    | HttpSoft  | Laminas   | Nyholm    | Slim      | Fatfree   |
|----------------------|-----------|-----------|-----------|-----------|-----------|-----------|
| Runs per second      | 18599     | 31938     | 22601     | 27999     | 18789     | 35200     |
| Average time per run | 0.0538 ms | 0.0313 ms | 0.0442 ms | 0.0357 ms | 0.0532 ms | 0.0284 ms |
| Total time           | 2.6882 s  | 1.5655 s  | 2.2122 s  | 1.7858 s  | 2.6611 s  | 1.4204 s  |

---
