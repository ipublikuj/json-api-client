<?php
/**
 * IRelationship.php
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
 * Relationship interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IRelationship extends StandardObjectInterface, IMetaMember
{
	public const DATA = NeomerxDocumentInterface::KEYWORD_DATA;
	public const META = NeomerxDocumentInterface::KEYWORD_META;

	/**
	 * Get the data member as a correctly casted object
	 *
	 * If this is a has-one relationship, a ResourceIdentifierInterface object or null will be returned. If it is
	 * a has-many relationship, a ResourceIdentifierCollectionInterface will be returned
	 *
	 * @return IResourceIdentifier|IResourceIdentifierCollection|NULL
	 *
	 * @throws Exceptions\RuntimeException if the value for the data member is not a valid relationship value
	 */
	public function getData();

	/**
	 * Get the data member as a resource identifier (has-one relationship)
	 *
	 * @return IResourceIdentifier
	 *
	 * @throws Exceptions\RuntimeException if the data member is not a resource identifier
	 */
	public function getIdentifier() : ?IResourceIdentifier;

	/**
	 * Is the data member a resource identifier?
	 *
	 * @return bool
	 */
	public function hasIdentifier() : bool;

	/**
	 * Is this a has-one relationship?
	 *
	 * @return bool
	 */
	public function isHasOne() : bool;

	/**
	 * Get the data member as a has-many relationship
	 *
	 * @return IResourceIdentifierCollection
	 *
	 * @throws Exceptions\RuntimeException if the data member is not an array
	 */
	public function getIdentifiers() : IResourceIdentifierCollection;

	/**
	 * Is this a has-many relationship?
	 *
	 * @return bool
	 */
	public function isHasMany() : bool;
}
