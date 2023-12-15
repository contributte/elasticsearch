<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\DI;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @method stdClass getConfig()
 */
class ElasticsearchExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'hosts' => Expect::arrayOf('string')->required(),
			'retries' => Expect::int(1),
		]);
	}

	public function beforeCompile(): void
	{
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('client'))
			->setType(Client::class)
			->setFactory([ClientBuilder::class, 'fromConfig'])
			->setArguments([(array) $config]);
	}

}
