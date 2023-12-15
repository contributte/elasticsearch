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
					hosts: [192.168.1.100:9999]
			NEON
			));
		})->build();

	Assert::type(Container::class, $container);
	Assert::type(Client::class, $container->getService('elasticsearch.client'));
});
