<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\Tracy;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Psr\Http\Client\ClientInterface;

/**
 * Factory for creating Elasticsearch client with optional query logging.
 */
class ClientFactory
{

	/**
	 * @param array{hosts?: array<string>, retries?: int, sslVerification?: bool, apiKey?: array<string>, basicAuthentication?: array<string>} $config
	 */
	public static function create(
		array $config,
		?ClientInterface $httpClient = null,
		?QueryLogger $logger = null,
	): Client
	{
		$builder = ClientBuilder::create();

		// Set hosts
		if (isset($config['hosts'])) {
			$builder->setHosts($config['hosts']);
		}

		// Set retries
		if (isset($config['retries'])) {
			$builder->setRetries($config['retries']);
		}

		// Set SSL verification
		if (isset($config['sslVerification'])) {
			$builder->setSSLVerification($config['sslVerification']);
		}

		// Set API key
		if (isset($config['apiKey'])) {
			$builder->setApiKey(...$config['apiKey']);
		}

		// Set basic authentication
		if (isset($config['basicAuthentication'])) {
			$builder->setBasicAuthentication(...$config['basicAuthentication']);
		}

		// Set custom HTTP client with logging wrapper if provided
		if ($httpClient !== null && $logger !== null) {
			$loggingClient = new LoggingHttpClient($httpClient, $logger);
			$builder->setHttpClient($loggingClient);
		} elseif ($httpClient !== null) {
			$builder->setHttpClient($httpClient);
		}

		return $builder->build();
	}

}
