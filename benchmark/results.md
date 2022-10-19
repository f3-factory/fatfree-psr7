
Run benchmarks on php 8.0+:

```
php benchmark.php fatfree 50000
```

## Test results (best of 3 on MacBook "13 2020)

| Runs: 50,000         | Guzzle     | HttpSoft  | Laminas   | Nyholm    | Slim      | Fatfree   |
|----------------------|------------|-----------|-----------|-----------|-----------|-----------|
| Runs per second      | 3380       | 6203      | 4288      | 6299      | 3762      | 6391      |
| Average time per run | 0.2958 ms  | 0.1643 ms | 0.2332 ms | 0.1587 ms | 0.2657 ms | 0.1565 ms |
| Total time           | 14.7924 s  | 8.2163 s  | 11.6589 s | 7.9368 s  | 13.2873 s | 7.8232 s  |

---
