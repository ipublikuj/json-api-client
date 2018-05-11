<?php
/**
 * IResourceIdentifierCollection.php
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

/**
 * Resource identifiers collection interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IResourceIdentifierCollection extends \IteratorAggregate, \Countable
{
	/**
	 * Does the collection contain the supplied identifier?
	 *
	 * @param IResourceIdentifier $identifier
	 *
	 * @return bool
	 */
	public function has(IResourceIdentifier $identifier) : bool;

	/**
	 * Get the collection as an array
	 *
	 * @return IResourceIdentifier[]
	 */
	public function getAll() : array;

	/**
	 * Is the collection empty?
	 *
	 * @return bool
	 */
	public function isEmpty() : bool;

	/**
	 * Is every identifier in the collection complete?
	 *
	 * @return bool
	 */
	public function isComplete() : bool;

	/**
	 * Does every identifier in the collection match the supplied type/any of the supplied types?
	 *
	 * @param string|string[] $typeOrTypes
	 *
	 * @return bool
	 */
	public function isOnly($typeOrTypes) : bool;

	/**
	 * Get an array of the ids of each identifier in the collection
	 *
	 * @return string[]
	 */
	public function getIds() : array;

	/**
	 * Map the collection to an array of type keys and id values
	 *
	 * For example, this JSON structure:
	 *
	 * ```
	 * [
	 *  {"type": "foo", "id": "1"},
	 *  {"type": "foo", "id": "2"},
	 *  {"type": "bar", "id": "99"}
	 * ]
	 * ```
	 *
	 * Will map to:
	 *
	 * ```
	 * [
	 *  "foo" => ["1", "2"],
	 *  "bar" => ["99"]
	 * ]
	 * ```
	 *
	 * If the method call is provided with the an array `['foo' => 'FooModel', 'bar' => 'FoobarModel']`, then the
	 * returned mapped array will be:
	 *
	 * ```
	 * [
	 *  "FooModel" => ["1", "2"],
	 *  "FoobarModel" => ["99"]
	 * ]
	 * ```
	 *
	 * @param string[]|NULL $typeMap if an array, map the identifier types to the supplied types.
	 *
	 * @return mixed
	 */
	public function map(?array $typeMap = NULL);
}