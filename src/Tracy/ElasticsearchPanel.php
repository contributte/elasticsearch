<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\Tracy;

use Tracy\IBarPanel;

/**
 * Tracy debugger bar panel for Elasticsearch queries.
 */
class ElasticsearchPanel implements IBarPanel
{

	/** @var array<string, mixed> */
	private array $config = [];

	public function __construct(
		private QueryLogger $logger,
	)
	{
	}

	/**
	 * @param array<string, mixed> $config
	 */
	public function setConfig(array $config): void
	{
		$this->config = $config;
	}

	/**
	 * Renders HTML code for custom tab.
	 */
	public function getTab(): string
	{
		// Variables used in template
		// phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
		$queriesNum = $this->logger->getQueryCount();
		// phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
		$totalTime = $this->logger->getTotalTime();

		ob_start();
		require __DIR__ . '/templates/tab.phtml';

		return (string) ob_get_clean();
	}

	/**
	 * Renders HTML code for custom panel.
	 */
	public function getPanel(): string
	{
		// Variables used in template
		// phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
		$queriesNum = $this->logger->getQueryCount();
		// phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
		$totalTime = $this->logger->getTotalTime();
		// phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
		$queries = $this->logger->getQueries();
		// phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
		$config = $this->maskSensitiveConfig($this->config);

		ob_start();
		require __DIR__ . '/templates/panel.phtml';

		return (string) ob_get_clean();
	}

	/**
	 * Mask sensitive configuration values.
	 *
	 * @param array<string, mixed> $config
	 * @return array<string, mixed>
	 */
	private function maskSensitiveConfig(array $config): array
	{
		$sensitiveKeys = ['apiKey', 'basicAuthentication'];

		foreach ($sensitiveKeys as $key) {
			if (isset($config[$key])) {
				$config[$key] = is_array($config[$key])
					? array_map(fn () => '***', $config[$key])
					: '***';
			}
		}

		return $config;
	}

}
