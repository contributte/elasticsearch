<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\DI;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Nette\DI\CompilerExtension;

class ElasticsearchExtension extends CompilerExtension
{

	/** @var mixed */
	public $defaults = [
			'hosts' => ['localhost'],
		];

	public function beforeCompile(): void
	{
		$config = $this->getConfig($this->defaults);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('clientBuilder'))
			->setClass(ClientBuilder::class)
			->setFactory([ClientBuilder::class, 'create'])
			->setArguments($config['hosts']);

		$builder->addDefinition($this->prefix('client'))
			->setClass(Client::class)
			->setFactory(['@' . $this->prefix('clientBuilder'), 'build']);
	}

}
