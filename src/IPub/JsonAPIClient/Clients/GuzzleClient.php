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
		if (!$this->httpContainsBody($request, $response)) {
			return NULL;
		}

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
		if (!$this->httpContainsBody($request, $response)) {
			return NULL;
		}

		return Objects\Error::create(Utils\Json::decode(($response ? (string) $response->getBody() : (string) $request->getBody()), Utils\Json::FORCE_ARRAY));
	}

	/**
	 * @param PsrRequest $request
	 * @param PsrResponse|NULL $response
	 *
	 * @return bool
	 */
	private function httpContainsBody(PsrRequest $request, ?PsrResponse $response = NULL) : bool
	{
		return $response ?
			$this->doesResponseHaveBody($request, $response) :
			$this->doesRequestHaveBody($request);
	}

	/**
	 * Does the HTTP request contain body content?
	 *
	 * The presence of a message-body in a request is signaled by the inclusion of a Content-Length or
	 * Transfer-Encoding header field in the request's message-headers.
	 * https://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.3
	 *
	 * However, some browsers send a Content-Length header with an empty string for e.g. GET requests
	 * without any message-body. Therefore rather than checking for the existence of a Content-Length
	 * header, we will allow an empty value to indicate that the request does not contain body.
	 *
	 * @param PsrRequest $request
	 *
	 * @return bool
	 */
	private function doesRequestHaveBody(PsrRequest $request) : bool
	{
		if ($request->hasHeader('Transfer-Encoding')) {
			return TRUE;
		}

		if (!$contentLength = $request->getHeader('Content-Length')) {
			return FALSE;
		}

		return 0 < $contentLength[0];
	}

	/**
	 * Does the HTTP response contain body content?
	 *
	 * For response messages, whether or not a message-body is included with a message is dependent
	 * on both the request method and the response status code (section 6.1.1). All responses to the
	 * HEAD request method MUST NOT include a message-body, even though the presence of entity-header
	 * fields might lead one to believe they do. All 1xx (informational), 204 (no content), and 304
	 * (not modified) responses MUST NOT include a message-body. All other responses do include a
	 * message-body, although it MAY be of zero length.
	 * https://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.3
	 *
	 * @param PsrRequest $request
	 * @param PsrResponse $response
	 *
	 * @return bool
	 */
	private function doesResponseHaveBody(PsrRequest $request, PsrResponse $response) : bool
	{
		if (strtoupper($request->getMethod()) === 'HEAD') {
			return FALSE;
		}

		$status = $response->getStatusCode();

		if ((100 <= $status && 200 > $status) || 204 === $status || 304 === $status) {
			return FALSE;
		}

		if ($response->hasHeader('Transfer-Encoding')) {
			return TRUE;
		}

		if (!$contentLength = $response->getHeader('Content-Length')) {
			return FALSE;
		}

		return 0 < $contentLength[0];
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
