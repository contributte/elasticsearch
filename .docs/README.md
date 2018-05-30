# Elasticsearch

Find out more about [elasticsearch-php](https://github.com/elastic/elasticsearch-php). And also about [Elasticsearch](https://www.elastic.co/guide/en/elasticsearch/client/php-api/5.0/index.html)

## Content

- [Usage - how to register](#usage)
- [Configuration - how to configure](#configuration)

## Usage

Register `elasticsearch` extension in your config file.

```yaml
extensions:
    elasticsearch: Contributte\Elasticsearch\DI\ElasticsearchExtension
```

## Configuration

We're using `ClientBuilder` class to build and setup `Client`. ClientBuilder internally use `Monolog`. 
You'll probably need to install monolog as well.

```
composer require monolog/monolog
```

### Minimal configuration

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
