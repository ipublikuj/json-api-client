<?php
/**
 * IClient.php
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

use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Exceptions\JsonApiException;

use IPub\JsonAPIClient\Http;

/**
 * HTTP client interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Clients
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IClient
{
	/**
	 * @param string $endpoint
	 * @param EncodingParametersInterface|NULL $parameters the parameters to send to the remote server
	 * @param array $options
	 *
	 * @return Http\IResponse
	 *
	 * @throws JsonApiException if the remote server replies with an error
	 */
	public function index(string $endpoint, EncodingParametersInterface $parameters = NULL, array $options = []) : Http\IResponse;

	/**
	 * Read the domain record from the remote JSON API.
	 *
	 * @param string $endpoint
	 * @param EncodingParametersInterface|NULL $parameters the parameters to send to the remote server
	 * @param array $options
	 *
	 * @return Http\IResponse
	 *
	 * @throws JsonApiException if the remote server replies with an error
	 */
	public function read(string $endpoint, EncodingParametersInterface $parameters = NULL, array $options = []) : Http\IResponse;

	/**
	 * @param string $endpoint
	 * @param mixed $record
	 * @param EncodingParametersInterface|NULL $parameters the parameters to send to the remote server
	 * @param array $options
	 *
	 * @return Http\IResponse
	 *
	 * @throws JsonApiException if the remote server replies with an error
	 */
	public function create(string $endpoint, $record, EncodingParametersInterface $parameters = NULL, array $options = []) : Http\IResponse;

	/**
	 * @param string $endpoint
	 * @param mixed $record
	 * @param string[]|NULL $fields the resource fields to send, if sending sparse field-sets
	 * @param EncodingParametersInterface|NULL $parameters the parameters to send to the remote server
	 * @param array $options
	 *
	 * @return Http\IResponse
	 *
	 * @throws JsonApiException if the remote server replies with an error
	 */
	public function update(string $endpoint, $record, array $fields = NULL, EncodingParametersInterface $parameters = NULL, array $options = []) : Http\IResponse;

	/**
	 * @param string $endpoint
	 * @param array $options
	 *
	 * @return Http\IResponse
	 *
	 * @throws JsonApiException if the remote server replies with an error
	 */
	public function delete(string $endpoint, array $options = []) : Http\IResponse;

	/**
	 * @param string $key
	 *
	 * @return void
	 */
	public function addApiKey(string $key) : void;

	/**
	 * @param string $token
	 *
	 * @return void
	 */
	public function addAuthorization(string $token) : void;

	/**
	 * @return void
	 */
	public function removeAuthorization() : void;

	/**
	 * @param string $header
	 * @param string $value
	 *
	 * @return void
	 */
	public function addHeader(string $header, string $value) : void;
}
