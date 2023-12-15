<?php declare(strict_types = 1);

use Contributte\Elasticsearch\DI\ElasticsearchExtension;
use Contributte\Tester\Toolkit;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Elastic\Elasticsearch\Client;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(function (Compiler $compiler): void {
			$compiler->addExtension('elasticsearch', new ElasticsearchExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
			elasticsearch:
					hosts:
						- localhost
						- 192.168.1.100:9200
			NEON
			));
		})->build();

	Assert::type(Container::class, $container);
	Assert::type(Client::class, $container->getService('elasticsearch.client'));
});

Toolkit::test(function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(function (Compiler $compiler): void {
			$compiler->addExtension('elasticsearch', new ElasticsearchExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
			elasticsearch:
					hosts:
						- localhost
					sslVerification: false
					apiKey:
						- testapikey
			NEON
			));
		})->build();

	Assert::type(Container::class, $container);
	Assert::type(Client::class, $container->getService('elasticsearch.client'));
});

Toolkit::test(function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(function (Compiler $compiler): void {
			$compiler->addExtension('elasticsearch', new ElasticsearchExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
			elasticsearch:
					hosts:
						- localhost
					apiKey: null
			NEON
			));
		})->build();

	Assert::type(Container::class, $container);
	Assert::type(Client::class, $container->getService('elasticsearch.client'));
});
