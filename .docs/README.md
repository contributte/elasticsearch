# Contributte Elasticsearch

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

```yaml
extensions:
    elasticsearch: Contributte\Elasticsearch\DI\ElasticsearchExtension
```

We're using `ClientBuilder` class to build and setup the `Client`. ClientBuilder internally uses `Monolog`, which you'll need to install as well.

```bash
composer require monolog/monolog
```

## Configuration

```yaml
elasticsearch:
    hosts:
      - 'localhost'
```

### Advanced configuration

```yaml
elasticsearch:
    hosts:
        -
            host: 'localhost'
            port: 9200
            scheme: 'https'
            user: 'foo'
            pass: 'bar'
```

**NOTE:** The `host` is required, others are recommended, but not necessary.
