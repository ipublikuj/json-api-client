<?php
/**
 * GuzzleClient.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Clients
 * @since          1.0.0
 *
 * @date           05.05.18
 */

declare(strict_types = 1);

namespace IPub\JsonAPIClient\Clients;

use Nette;
use Nette\Http as NHttp;
use Nette\Utils;

use GuzzleHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;

use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Neomerx\JsonApi\Factories\Factory;

use Psr\Http\Message\RequestInterface as PsrRequest;
use Psr\Http\Message\ResponseInterface as PsrResponse;

use IPub\JsonAPIClient\Encoders;
use IPub\JsonAPIClient\Exceptions;
use IPub\JsonAPIClient\Http;
use IPub\JsonAPIClient\Objects;
use IPub\JsonAPIClient\Schemas;

/**
 * Guzzle client service
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Clients
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class GuzzleClient implements IClient
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	use TSendsRequests;

	/**
	 * Additional request headers
	 *
	 * @var string[]
	 */
	private $headers = [];

	/**
	 * @var Client
	 */
	private $http;

	/**
	 * @param string|NULL $baseUri
	 * @param Schemas\SchemaProvider $schemaProvider
	 * @param Client|NULL $client
	 */
	public function __construct(
		?string $baseUri = NULL,
		Schemas\SchemaProvider $schemaProvider,
		Client $client = NULL
	) {
		if ($client === NULL && $baseUri === NULL) {
			throw new Exceptions\InvalidStateException('You have to define base_uri or client to be able use api client.');
		}

		$this->http = $client !== NULL ? $client : $this->createClient($baseUri);

		$factory = new Factory;
		$this->schemas = $factory->createContainer($schemaProvider->getMapping());
		$this->serializer = new Encoders\Encoder($factory, $this->schemas, new EncoderOptions(
			JSON_PRETTY_PRINT
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function index(string $endpoint, EncodingParametersInterface $parameters = NULL, array $options = []) : Http\IResponse
	{
		$options = $this->mergeOptions([
			GuzzleHttp\RequestOptions::HEADERS => $this->jsonApiHeaders(FALSE),
			GuzzleHttp\RequestOptions::QUERY   => $parameters ? $this->parseSearchQuery($parameters) : NULL,
		], $options);

		return $this->request(NHttp\IRequest::GET, $endpoint, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function read(string $endpoint, EncodingParametersInterface $parameters = NULL, array $options = []) : Http\IResponse
	{
		$options = $this->mergeOptions([
			GuzzleHttp\RequestOptions::HEADERS => $this->jsonApiHeaders(FALSE),
			GuzzleHttp\RequestOptions::QUERY   => $parameters ? $this->parseQuery($parameters) : NULL,
		], $options);

		return $this->request(NHttp\IRequest::GET, $endpoint, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function create(string $endpoint, $record, EncodingParametersInterface $parameters = NULL, array $options = []) : Http\IResponse
	{
		if (!is_object($record)) {
			throw new Exceptions\InvalidArgumentException('Provided data entity is not an object.');
		}

		return $this->sendRecord($endpoint, NHttp\IRequest::POST, $this->serializeRecord($record), $parameters, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function update(string $endpoint, $record, array $fields = NULL, EncodingParametersInterface $parameters = NULL, array $options = []) : Http\IResponse
	{
		if (!is_object($record)) {
			throw new Exceptions\InvalidArgumentException('Provided data entity is not an object.');
		}

		return $this->sendRecord($endpoint, NHttp\IRequest::PATCH, $this->serializeRecord($record, $fields), $parameters, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(string $endpoint, array $options = []) : Http\IResponse
	{
		$options = $this->mergeOptions([
			GuzzleHttp\RequestOptions::HEADERS => $this->jsonApiHeaders(FALSE)
		], $options);

		return $this->request(NHttp\IRequest::DELETE, $endpoint, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function addApiKey(string $key) : void
	{
		$this->addHeader('X-Api-Key', $key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function addAuthorization(string $token) : void
	{
		$this->addHeader('Authorization', 'Bearer ' . $token);
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeAuthorization() : void
	{
		if (isset($this->headers['Authorization'])) {
			unset($this->headers['Authorization']);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function addHeader(string $header, string $value) : void
	{
		$this->headers[$header] = $value;
	}

	/**
	 * @param string $endpoint
	 * @param string $method
	 * @param array $serializedRecord the encoded record
	 * @param EncodingParametersInterface|NULL $parameters
	 * @param array $options
	 *
	 * @return Http\IResponse
	 *
	 * @throws JsonApiException
	 * @throws GuzzleHttp\Exception\GuzzleException
	 * @throws Utils\JsonException
	 */
	protected function sendRecord(string $endpoint, string $method, array $serializedRecord, EncodingParametersInterface $parameters = NULL, array $options = []) : Http\IResponse
	{
		$options = $this->mergeOptions([
			GuzzleHttp\RequestOptions::HEADERS => $this->jsonApiHeaders(TRUE),
			GuzzleHttp\RequestOptions::QUERY   => $parameters ? $this->parseQuery($parameters) : NULL,
		], $options);

		$options['json'] = $serializedRecord;

		return $this->request($method, $endpoint, $options);
	}

	/**
	 * @param array $new
	 * @param array $existing
	 *
	 * @return array
	 */
	protected function mergeOptions(array $new, array $existing) : array
	{
		return array_replace_recursive($new, $existing);
	}

	/**
	 * @param string $method
	 * @param string $uri
	 * @param array $options
	 *
	 * @return Http\IResponse
	 *
	 * @throws JsonApiException
	 * @throws GuzzleHttp\Exception\GuzzleException
	 * @throws Utils\JsonException
	 */
	protected function request(string $method, string $uri, array $options = []) : Http\IResponse
	{
		$request = new Request($method, $uri);

		try {
			$response = $this->http->send($request, $options);

		} catch (BadResponseException $ex) {
			throw $this->parseErrorResponse($request, $ex);
		}

		return new Http\Response($response, $this->createDocumentObject($request, $response));
	}

	/**
	 * Safely parse an error response.
	 *
	 * This method wraps decoding the body content of the provided exception, so that
	 * another exception is not thrown while trying to parse an existing exception.
	 *
	 * @param PsrRequest $request
	 * @param BadResponseException $ex
	 *
	 * @return JsonApiException
	 */
	private function parseErrorResponse(PsrRequest $request, BadResponseException $ex) : JsonApiException
	{
		try {
			$response = $ex->getResponse();

			$document = $response ? $this->createDocumentObject($request, $response) : NULL;

			$errors = $document && $document->getErrors() !== NULL ? $document->getErrors() : [$this->createErrorObject($request, $response)];

			$statusCode = $response ? $response->getStatusCode() : 0;

		} catch (\Exception $e) {
			$errors = [];
			$statusCode = 0;
		}

		return new JsonApiException($errors, $statusCode, $ex);
	}

	/**
	 * @param PsrRequest $request
	 * @param PsrResponse|NULL $response
	 *
	 * @return Objects\IDocument|NULL
	 *
	 * @throws Utils\JsonException
	 */
	private function createDocumentObject(PsrRequest $request, PsrResponse $response = NULL) : ?Objects\IDocument
	{
		return new Objects\Document(Utils\Json::decode(($response ? (string) $response->getBody() : (string) $request->getBody())));
	}

	/**
	 * @param PsrRequest $request
	 * @param PsrResponse|NULL $response
	 *
	 * @return Objects\IMutableError|NULL
	 *
	 * @throws Utils\JsonException
	 */
	private function createErrorObject(PsrRequest $request, PsrResponse $response = NULL) : ?Objects\IMutableError
	{
		return Objects\Error::create(Utils\Json::decode(($response ? (string) $response->getBody() : (string) $request->getBody()), Utils\Json::FORCE_ARRAY));
	}

	/**
	 * @param string $baseUri
	 *
	 * @return Client
	 */
	private function createClient(string $baseUri) : Client
	{
		$client = new Client([
			'base_uri' => $baseUri,
		]);

		return $client;
	}
}
