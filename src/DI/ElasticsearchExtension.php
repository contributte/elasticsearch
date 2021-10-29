<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\DI;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
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
				Expect::type(Statement::class),
				Expect::structure([
					'host' => Expect::anyOf(Expect::string(), Expect::type(Statement::class))->required(),
					'port' => Expect::anyOf(Expect::int(), Expect::type(Statement::class)),
					'scheme' => Expect::anyOf(Expect::string(), Expect::type(Statement::class)),
					'path' => Expect::anyOf(Expect::string(), Expect::type(Statement::class)),
					'user' => Expect::anyOf(Expect::string(), Expect::type(Statement::class)),
					'pass' => Expect::anyOf(Expect::string(), Expect::type(Statement::class)),
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
