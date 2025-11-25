# Contributte / Elasticsearch

Find out more about [elasticsearch-php](https://github.com/elastic/elasticsearch-php) and also about [Elasticsearch](https://www.elastic.co/guide/en/elasticsearch/client/php-api/5.0/index.html)

## Content

- [Setup](#setup)
- [Configuration](#configuration)
- [Tracy Panel](#tracy-panel)

## Setup

Install package

```bash
composer require contributte/elasticsearch
```

Register extension.

```neon
extensions:
	elasticsearch: Contributte\Elasticsearch\DI\ElasticsearchExtension
```

## Configuration

```neon
elasticsearch:
	hosts: [localhost]
	sslVerification: false
	apiKey: [testapikey]
```

**NOTE:** The `host` is required, others are recommended, but not necessary.

## Tracy Panel

Enable the Tracy debugger panel to monitor Elasticsearch queries during development.

```neon
elasticsearch:
	hosts: [localhost]
	debug: true
```

The Tracy panel displays:
- Number of queries and total execution time
- HTTP method and endpoint for each request
- Request body and response data (expandable)
- Response status codes
- Backtrace showing where each query originated
- Configuration details (with sensitive data masked)

**NOTE:** The `debug` option requires the `tracy/tracy` package. Install it via:

```bash
composer require tracy/tracy
```
