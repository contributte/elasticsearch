<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\Tracy;

/**
 * Stores Elasticsearch query information for Tracy panel debugging.
 */
class QueryLogger
{

	/** @var array<int, array{method: string, endpoint: string, params: array<string, mixed>, body: mixed, response: mixed, statusCode: int, duration: float, backtrace: array<int, array{file?: string, line?: int, class?: string, function?: string}>}> */
	private array $queries = [];

	private float $totalTime = 0.0;

	/**
	 * @param array<string, mixed> $params
	 * @param array<int, array{file?: string, line?: int, class?: string, function?: string}> $backtrace
	 */
	public function logQuery(
		string $method,
		string $endpoint,
		array $params,
		mixed $body,
		mixed $response,
		int $statusCode,
		float $duration,
		array $backtrace = [],
	): void
	{
		$this->queries[] = [
			'method' => $method,
			'endpoint' => $endpoint,
			'params' => $params,
			'body' => $body,
			'response' => $response,
			'statusCode' => $statusCode,
			'duration' => $duration,
			'backtrace' => $backtrace,
		];

		$this->totalTime += $duration;
	}

	/**
	 * @return array<int, array{method: string, endpoint: string, params: array<string, mixed>, body: mixed, response: mixed, statusCode: int, duration: float, backtrace: array<int, array{file?: string, line?: int, class?: string, function?: string}>}>
	 */
	public function getQueries(): array
	{
		return $this->queries;
	}

	public function getQueryCount(): int
	{
		return count($this->queries);
	}

	public function getTotalTime(): float
	{
		return $this->totalTime;
	}

	public function clear(): void
	{
		$this->queries = [];
		$this->totalTime = 0.0;
	}

}
