# Contributte / Elasticsearch

Find out more about [elasticsearch-php](https://github.com/elastic/elasticsearch-php) and also about [Elasticsearch](https://www.elastic.co/guide/en/elasticsearch/client/php-api/5.0/index.html)

## Content

- [Setup](#setup)
- [Configuration](#configuration)

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
