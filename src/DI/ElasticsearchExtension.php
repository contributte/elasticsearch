<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\DI;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Nette\DI\CompilerExtension;

class ElasticsearchExtension extends CompilerExtension
{

	/** @var mixed[] */
	private $defaults = [
		'hosts' => ['localhost'],
	];

	public function beforeCompile(): void
	{
		if (!isset($this->config['hosts'])) {
			$this->defaults['hosts'] = [];
		}

		$config = $this->getConfig();

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('clientBuilder'))
			->setType(ClientBuilder::class)
			->setFactory([ClientBuilder::class, 'create'])
			->setArguments($config['hosts']);

		$builder->addDefinition($this->prefix('client'))
			->setType(Client::class)
			->setFactory(['@' . $this->prefix('clientBuilder'), 'build']);
	}

}
