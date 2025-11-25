<?php declare(strict_types = 1);

namespace Contributte\Elasticsearch\Tracy;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * PSR-18 HTTP client wrapper that logs requests and responses for Tracy debugging.
 */
class LoggingHttpClient implements ClientInterface
{

	public function __construct(
		private ClientInterface $innerClient,
		private QueryLogger $logger,
	)
	{
	}

	public function sendRequest(RequestInterface $request): ResponseInterface
	{
		$startTime = microtime(true);

		// Extract request information
		$method = $request->getMethod();
		$uri = $request->getUri();
		$endpoint = $uri->getPath();

		$params = [];
		parse_str($uri->getQuery(), $params);
		/** @var array<string, mixed> $params */
		$params = $params;

		// Get request body
		$body = (string) $request->getBody();
		$request->getBody()->rewind();

		$bodyDecoded = $body !== '' ? json_decode($body, true) : null;

		// Capture backtrace (filter out internal calls)
		$backtrace = $this->captureBacktrace();

		try {
			$response = $this->innerClient->sendRequest($request);
			$duration = microtime(true) - $startTime;

			// Get response body
			$responseBody = (string) $response->getBody();
			$response->getBody()->rewind();

			$responseDecoded = $responseBody !== '' ? json_decode($responseBody, true) : null;

			$this->logger->logQuery(
				$method,
				$endpoint,
				$params,
				$bodyDecoded,
				$responseDecoded,
				$response->getStatusCode(),
				$duration,
				$backtrace,
			);

			return $response;
		} catch (\Throwable $e) {
			$duration = microtime(true) - $startTime;

			$this->logger->logQuery(
				$method,
				$endpoint,
				$params,
				$bodyDecoded,
				['error' => $e->getMessage()],
				0,
				$duration,
				$backtrace,
			);

			throw $e;
		}
	}

	/**
	 * Capture backtrace filtering out internal Elasticsearch client calls.
	 *
	 * @return array<int, array{file?: string, line?: int, class?: string, function?: string}>
	 */
	private function captureBacktrace(): array
	{
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$filtered = [];

		foreach ($backtrace as $trace) {
			// Skip internal Elasticsearch and HTTP client traces
			$class = $trace['class'] ?? '';
			if (
				str_starts_with($class, 'Elastic\\Elasticsearch\\')
				|| str_starts_with($class, 'Contributte\\Elasticsearch\\Tracy\\')
				|| str_starts_with($class, 'GuzzleHttp\\')
				|| str_starts_with($class, 'Http\\')
			) {
				continue;
			}

			// Include trace if it has a file
			if (isset($trace['file'])) {
				$filtered[] = [
					'file' => $trace['file'],
					'line' => $trace['line'] ?? 0,
					'class' => $trace['class'] ?? null,
					'function' => $trace['function'],
				];
			}
		}

		/** @var array<int, array{file?: string, line?: int, class?: string, function?: string}> $result */
		$result = array_slice($filtered, 0, 10); // Limit to 10 entries

		return $result;
	}

}
