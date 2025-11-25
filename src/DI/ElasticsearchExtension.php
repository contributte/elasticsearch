<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\DI;

use Contributte\Elasticsearch\Tracy\ClientFactory;
use Contributte\Elasticsearch\Tracy\ElasticsearchPanel;
use Contributte\Elasticsearch\Tracy\QueryLogger;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;
use Tracy\Bar;

/**
 * @method stdClass getConfig()
 */
class ElasticsearchExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'hosts' => Expect::arrayOf(Expect::string())->required()->min(1),
			'retries' => Expect::int(1),
			'sslVerification' => Expect::bool(true),
			'apiKey' => Expect::anyOf(Expect::arrayOf(Expect::string())->min(1)->max(2), null),
			'basicAuthentication' => Expect::anyOf(Expect::arrayOf(Expect::string())->min(2)->max(2), null),
			'debug' => Expect::bool(false),
		]);
	}

	public function beforeCompile(): void
	{
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		$debug = $config->debug && class_exists(Bar::class);

		// Filter out null values and remove debug from config passed to client
		$clientConfig = array_filter((array) $config, fn ($value) => $value !== null);
		unset($clientConfig['debug']);

		if ($debug) {
			// Register QueryLogger
			$builder->addDefinition($this->prefix('queryLogger'))
				->setType(QueryLogger::class);

			// Register ElasticsearchPanel
			$panel = $builder->addDefinition($this->prefix('panel'))
				->setType(ElasticsearchPanel::class)
				->setArguments([new Statement('@' . $this->prefix('queryLogger'))]);

			$panel->addSetup('setConfig', [$clientConfig]);

			// Register panel with Tracy Bar
			if ($builder->hasDefinition('tracy.bar')) {
				$tracyBar = $builder->getDefinition('tracy.bar');
				assert($tracyBar instanceof ServiceDefinition);
				$tracyBar->addSetup('addPanel', [new Statement('@' . $this->prefix('panel'))]);
			}

			// Create client with logging using ClientFactory
			$builder->addDefinition($this->prefix('client'))
				->setType(Client::class)
				->setFactory([ClientFactory::class, 'create'], [
					$clientConfig,
					new Statement('Http\Discovery\Psr18ClientDiscovery::find'),
					new Statement('@' . $this->prefix('queryLogger')),
				]);
		} else {
			// Create client without logging
			$builder->addDefinition($this->prefix('client'))
				->setType(Client::class)
				->setFactory([ClientBuilder::class, 'fromConfig'])
				->setArguments([$clientConfig]);
		}
	}

}
