<?php
/**
 * SendsRequestsTrait.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Clients
 * @since          1.0.0
 *
 * @date           05.05.18
 */

declare(strict_types = 1);

namespace IPub\JsonAPIClient\Clients;

use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Contracts\Http\Query\BaseQueryParserInterface;
use Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Http\Headers\MediaType;

use IPub\JsonAPIClient\Encoders;

/**
 * Sender trait
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Clients
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @property array $headers
 */
trait TSendsRequests
{
	/**
	 * @var ContainerInterface
	 */
	protected $schemas;

	/**
	 * @var Encoders\ISerializer
	 */
	protected $serializer;

	/**
	 * @param mixed $record
	 * @param string[]|NULL $fields
	 *
	 * @return array
	 */
	protected function serializeRecord($record, array $fields = NULL) : array
	{
		$parameters = NULL;

		if ($fields !== NULL) {
			$resourceType = $this->schemas->getSchema($record)->getResourceType();
			$parameters = $this->createQueryParameters(NULL, [$resourceType => $fields]);
		}

		return $this->serializer->serializeData($record, $parameters);
	}

	/**
	 * @param bool $body whether HTTP request body is being sent
	 *
	 * @return string[]
	 */
	protected function jsonApiHeaders($body = FALSE) : array
	{
		$headers = ['Accept' => MediaType::JSON_API_MEDIA_TYPE];

		if ($body === TRUE) {
			$headers['Content-Type'] = MediaType::JSON_API_MEDIA_TYPE;
		}

		$headers = array_merge($this->headers, $headers);

		return $headers;
	}

	/**
	 * @param EncodingParametersInterface $parameters
	 *
	 * @return array
	 */
	protected function parseQuery(EncodingParametersInterface $parameters) : array
	{
		return array_filter(array_merge(/*(array) $parameters->getUnrecognizedParameters(),*/
			[
				BaseQueryParserInterface::PARAM_INCLUDE =>
					implode(',', (array) $parameters->getIncludePaths()),
				BaseQueryParserInterface::PARAM_FIELDS  =>
					$this->parseQueryFieldsets((array) $parameters->getFieldSets()),
			], []));
	}

	/**
	 * @param EncodingParametersInterface $parameters
	 *
	 * @return array
	 */
	protected function parseSearchQuery(EncodingParametersInterface $parameters) : array
	{
		return array_filter(array_merge($this->parseQuery($parameters)/*, [
			BaseQueryParserInterface::PARAM_SORT   =>
				implode(',', (array) $parameters->getSortParameters()),
			BaseQueryParserInterface::PARAM_PAGE   =>
				$parameters->getPaginationParameters(),
			BaseQueryParserInterface::PARAM_FILTER =>
				$parameters->getFilteringParameters(),
		]*/, []));
	}

	/**
	 * @param array $fieldsets
	 *
	 * @return string[]
	 */
	private function parseQueryFieldsets(array $fieldsets) : array
	{
		return array_map(function ($values) : string {
			return implode(',', (array) $values);
		}, $fieldsets);
	}

	/**
	 * @param array|NULL $includePaths
	 * @param array|NULL $fieldSets
	 *
	 * @return EncodingParametersInterface
	 */
	private function createQueryParameters(array $includePaths = NULL, array $fieldSets = NULL) : EncodingParametersInterface
	{
		return new EncodingParameters($includePaths, $fieldSets);
	}
}
