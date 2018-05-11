<?php
/**
 * IResourceObject.php
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
 * Resource object interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IResourceObject extends StandardObjectInterface, IMetaMember
{
	public const TYPE = NeomerxDocumentInterface::KEYWORD_TYPE;
	public const ID = NeomerxDocumentInterface::KEYWORD_ID;
	public const ATTRIBUTES = NeomerxDocumentInterface::KEYWORD_ATTRIBUTES;
	public const RELATIONSHIPS = NeomerxDocumentInterface::KEYWORD_RELATIONSHIPS;
	public const META = NeomerxDocumentInterface::KEYWORD_META;

	/**
	 * Get the type member
	 *
	 * @return string
	 *
	 * @throws Exceptions\RuntimeException if no type is set, is empty or is not a string
	 */
	public function getType() : string;

	/**
	 * @return string|int
	 *
	 * @throws Exceptions\RuntimeException if no id is set, is not a string or integer, or is an empty string
	 */
	public function getId();

	/**
	 * @return bool
	 */
	public function hasId() : bool;

	/**
	 * Get the type and id members as a resource identifier object
	 *
	 * @return IResourceIdentifier
	 *
	 * @throws Exceptions\RuntimeException if the type and/or id members are not valid
	 */
	public function getIdentifier() : IResourceIdentifier;

	/**
	 * @return StandardObjectInterface
	 *
	 * @throws Exceptions\RuntimeException if the attributes member is present and is not an object
	 */
	public function getAttributes() : StandardObjectInterface;

	/**
	 * @return bool
	 */
	public function hasAttributes() : bool;

	/**
	 * @return IRelationships
	 *
	 * @throws Exceptions\RuntimeException if the relationships member is present and is not an object
	 */
	public function getRelationships() : IRelationships;

	/**
	 * @return bool
	 */
	public function hasRelationships() : bool;

	/**
	 * Get a relationship object by its key
	 *
	 * @param string $key
	 *
	 * @return IRelationship|NULL the relationship object or null if it is not present
	 *
	 * @throws Exceptions\RuntimeException
	 */
	public function getRelationship(string $key) : IRelationship;
}
