<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\DI;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
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
			'hosts'           => Expect::arrayOf(Expect::string())->required()->min(1),
			'retries'         => Expect::int(1),
			'sslVerification' => Expect::bool(true),
			'apiKey'          => Expect::anyOf(Expect::arrayOf(Expect::string())->min(1)->max(2), null),
			'basicAuthentication' => Expect::anyOf(Expect::arrayOf(Expect::string())->min(2)->max(2), null),
		]);
	}

	public function beforeCompile(): void
	{
		$config  = $this->config;
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('client'))
			->setType(Client::class)
			->setFactory([ClientBuilder::class, 'fromConfig'])
			->setArguments(
				[
					array_filter((array) $config),
				]
			);
	}

}
