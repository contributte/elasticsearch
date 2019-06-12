<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\DI;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @property-read stdClass $config
 */
class ElasticsearchExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'hosts' => Expect::anyOf(Expect::arrayOf('string'), Expect::structure([
				'host' => Expect::string()->required(),
				'port' => Expect::int(),
				'schema' => Expect::string(),
				'path' => Expect::string(),
				'user' => Expect::string(),
				'pass' => Expect::string(),
			])),
			'retries' => Expect::int(),
		]);
	}

	public function beforeCompile(): void
	{
		$config = $this->config;
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('clientBuilder'))
			->setType(ClientBuilder::class)
			->setFactory([ClientBuilder::class, 'create'])
			->setArguments($config->hosts);

		$builder->addDefinition($this->prefix('client'))
			->setType(Client::class)
			->setFactory(['@' . $this->prefix('clientBuilder'), 'build']);
	}

}
