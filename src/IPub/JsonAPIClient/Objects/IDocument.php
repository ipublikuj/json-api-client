<?php
/**
 * IDocument.php
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
use Neomerx\JsonApi\Exceptions\ErrorCollection;

use IPub\JsonAPIClient\Exceptions;

/**
 * Response document interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IDocument extends StandardObjectInterface, IMetaMember
{
	public const DATA = NeomerxDocumentInterface::KEYWORD_DATA;
	public const META = NeomerxDocumentInterface::KEYWORD_META;
	public const INCLUDED = NeomerxDocumentInterface::KEYWORD_INCLUDED;
	public const ERRORS = NeomerxDocumentInterface::KEYWORD_ERRORS;

	/**
	 * Get the data member of the document as a standard object or array
	 *
	 * @return StandardObjectInterface|array|NULL
	 *
	 * @throws Exceptions\RuntimeException if the data member is not present, or is not an object, array or null
	 */
	public function getData();

	/**
	 * Get the data member as a resource object
	 *
	 * @return IResourceObject
	 *
	 * @throws Exceptions\RuntimeException if the data member is not an object or is not present
	 */
	public function getResource() : IResourceObject;

	/**
	 * Get the data member as a resource object collection
	 *
	 * @return IResourceObjectCollection
	 *
	 * @throws Exceptions\RuntimeException if the data member is not an array or is not present
	 */
	public function getResources() : IResourceObjectCollection;

	/**
	 * Get the document as a relationship
	 *
	 * @return IRelationship
	 */
	public function getRelationship() : IRelationship;

	/**
	 * Get the included member as a resource object collection
	 *
	 * @return IResourceObjectCollection|NULL the resources or null if the included member is not present
	 */
	public function getIncluded() : ?IResourceObjectCollection;

	/**
	 * Get the errors member as an error collection.
	 *
	 * @return ErrorCollection|NULL the errors or null if the error member is not present
	 */
	public function getErrors() : ?ErrorCollection;
}
