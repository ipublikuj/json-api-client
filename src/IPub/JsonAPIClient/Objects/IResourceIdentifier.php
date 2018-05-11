<?php
/**
 * IResourceIdentifier.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 * @since          1.0.0
 *
 * @date           05.05.18
 */

declare(strict_types = 1);

namespace IPub\JsonAPIClient\Objects;

use CloudCreativity\Utils\Object\StandardObjectInterface;

use Neomerx\JsonApi\Contracts\Document\DocumentInterface as NeomerxDocumentInterface;

use IPub\JsonAPIClient\Exceptions;

/**
 * Resource identifier interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IResourceIdentifier extends StandardObjectInterface, IMetaMember
{
	public const TYPE = NeomerxDocumentInterface::KEYWORD_TYPE;
	public const ID = NeomerxDocumentInterface::KEYWORD_ID;
	public const META = NeomerxDocumentInterface::KEYWORD_META;

	/**
	 * @return string
	 *
	 * @throws Exceptions\RuntimeException if the type member is not present, or is not a string, or is an empty string
	 */
	public function getType() : string;

	/**
	 * @return bool
	 */
	public function hasType() : bool;

	/**
	 * Returns true if the current type matches the supplied type, or any of the supplied types
	 *
	 * @param string|string[] $typeOrTypes
	 *
	 * @return bool
	 */
	public function isType($typeOrTypes) : bool;

	/**
	 * From the supplied array, return the value where the current type is the key
	 *
	 * @param array $types
	 *
	 * @return mixed
	 *
	 * @throws Exceptions\RuntimeException if the current type is not one of those in the supplied $types
	 */
	public function mapType(array $types);

	/**
	 * @return string
	 *
	 * @throws Exceptions\RuntimeException if the id member is not present, or is not a string, or is an empty string
	 */
	public function getId() : string;

	/**
	 * @return bool
	 */
	public function hasId() : bool;

	/**
	 * Whether both a type and an id are present
	 *
	 * @return bool
	 */
	public function isComplete();

	/**
	 * Do the type and id match?
	 *
	 * @param IResourceIdentifier $identifier
	 *
	 * @return bool
	 */
	public function isSame(IResourceIdentifier $identifier) : bool;

	/**
	 * Get a string representation of the identifier
	 *
	 * @return string
	 */
	public function toString() : string;
}
