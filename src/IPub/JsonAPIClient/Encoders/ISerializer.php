<?php
/**
 * ISerializer.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Encoders
 * @since          1.0.0
 *
 * @date           05.05.18
 */

declare(strict_types = 1);

namespace IPub\JsonAPIClient\Encoders;

use Iterator;
use Neomerx\JsonApi\Contracts\Document\ErrorInterface;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Exceptions\ErrorCollection;

/**
 * Serializer interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Encoders
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface ISerializer extends EncoderInterface
{
	/**
	 * @param array|Iterator|NULL $data
	 * @param EncodingParametersInterface|NULL $parameters
	 *
	 * @return array
	 */
	public function serializeData($data, EncodingParametersInterface $parameters = NULL);

	/**
	 * @param array|Iterator|NULL $data
	 * @param EncodingParametersInterface|NULL $parameters
	 *
	 * @return array
	 */
	public function serializeIdentifiers($data, EncodingParametersInterface $parameters = NULL);

	/**
	 * @param ErrorInterface $error
	 *
	 * @return array
	 */
	public function serializeError(ErrorInterface $error);

	/**
	 * @param ErrorInterface[]|ErrorCollection $errors
	 *
	 * @return array
	 */
	public function serializeErrors($errors);

	/**
	 * @param array $meta
	 *
	 * @return array
	 */
	public function serializeMeta($meta);
}
