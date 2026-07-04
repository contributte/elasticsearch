![](https://heatbadger.now.sh/github/readme/contributte/elasticsearch/)

<p align=center>
  <a href="https://github.com/contributte/elasticsearch/actions"><img src="https://badgen.net/github/checks/contributte/elasticsearch/master"></a>
  <a href="https://coveralls.io/r/contributte/elasticsearch"><img src="https://badgen.net/coveralls/c/github/contributte/elasticsearch"></a>
  <a href="https://packagist.org/packages/contributte/elasticsearch"><img src="https://badgen.net/packagist/dm/contributte/elasticsearch"></a>
  <a href="https://packagist.org/packages/contributte/elasticsearch"><img src="https://badgen.net/packagist/v/contributte/elasticsearch"></a>
</p>
<p align=center>
  <a href="https://packagist.org/packages/contributte/elasticsearch"><img src="https://badgen.net/packagist/php/contributte/elasticsearch"></a>
  <a href="https://github.com/contributte/elasticsearch"><img src="https://badgen.net/github/license/contributte/elasticsearch"></a>
  <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
  <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
  <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Nette integration for [elasticsearch-php](https://github.com/elastic/elasticsearch-php) and [Elasticsearch](https://www.elastic.co/guide/en/elasticsearch/client/php-api/5.0/index.html).

## Versions

| State       | Version | Branch   | Nette | PHP     |
|-------------|---------|----------|-------|---------|
| dev         | `^0.6`  | `master` | 3.2+  | `>=8.1` |
| stable      | `^0.5`  | `master` | 3.2+  | `>=8.1` |

## Installation

To install the latest version of `contributte/elasticsearch` use [Composer](https://getcomposer.org).

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

**NOTE:** The `hosts` option is required, others are recommended, but not necessary.

## Development

See [how to contribute](https://contributte.org/contributing.html) to this package.

This package is currently maintaining by these authors.

<a href="https://github.com/f3l1x">
    <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>
<a href="https://github.com/vojtamares">
  <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/7180610?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners.html) **contributte** development team.
Also thank you for using this package.
