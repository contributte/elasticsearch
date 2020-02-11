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
			'hosts' => Expect::arrayOf(Expect::anyOf(
				Expect::string(),
				Expect::structure([
					'host' => Expect::string()->required(),
					'port' => Expect::int(),
					'scheme' => Expect::string(),
					'path' => Expect::string(),
					'user' => Expect::string(),
					'pass' => Expect::string(),
				])->castTo('array')
			))->required()->min(1),
			'retries' => Expect::int(1),
		]);
	}

	public function beforeCompile(): void
	{
		$config = $this->config;
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('client'))
			->setType(Client::class)
			->setFactory([ClientBuilder::class, 'fromConfig'])
			->setArguments([(array) $config]);
	}

}
