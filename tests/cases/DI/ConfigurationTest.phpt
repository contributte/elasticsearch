<?php declare(strict_types = 1);

use Contributte\Elasticsearch\DI\ElasticsearchExtension;
use Elastic\Elasticsearch\Client;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\DI\InvalidConfigurationException;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('elasticsearch', new ElasticsearchExtension());
		$compiler->loadConfig(FileMock::create('
			elasticsearch:
					hosts:
					    - localhost
		', 'neon'));
	}, '1a');

	/** @var Container $container */
	$container = new $class();

	Assert::type(Container::class, $container);
	Assert::type(Client::class, $container->getService('elasticsearch.client'));
});

test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	// Elasticsearch removed Extended Host Configuration support in 8.0
	Assert::exception(function () use ($loader) {
		return $loader->load(function (Compiler $compiler): void {
			$compiler->addExtension('elasticsearch', new ElasticsearchExtension());
			$compiler->loadConfig(
				FileMock::create(
					'
			elasticsearch:
					hosts:
					    - localhost
					    -
					    	host: 192.168.1.100
					    	port: 9999
		',
					'neon'
				)
			);
		}, '1b');
	}, InvalidConfigurationException::class);
});


test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('elasticsearch', new ElasticsearchExtension());
		$compiler->loadConfig(FileMock::create('
			elasticsearch:
					hosts:
					    - localhost
					sslVerification: false
					apiKey:
						- testapikey
		', 'neon'));
	}, '1c');

	/** @var Container $container */
	$container = new $class();

	Assert::type(Container::class, $container);
	Assert::type(Client::class, $container->getService('elasticsearch.client'));
});


test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('elasticsearch', new ElasticsearchExtension());
		$compiler->loadConfig(FileMock::create('
			elasticsearch:
					hosts:
					    - localhost
					apiKey: null
		', 'neon'));
	}, '1d');

	/** @var Container $container */
	$container = new $class();

	Assert::type(Container::class, $container);
	Assert::type(Client::class, $container->getService('elasticsearch.client'));
});
