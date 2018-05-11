<?php
/**
 * ResourceObjectCollection.php
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

use IPub\JsonAPIClient\Exceptions;

/**
 * Resource objects collection
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class ResourceObjectCollection implements IResourceObjectCollection
{
	/**
	 * @var IResourceObject[]
	 */
	private $stack = [];

	/**
	 * @param array $resources
	 *
	 * @return ResourceObjectCollection
	 */
	public static function create(array $resources)
	{
		$resources = array_map(function ($resource) {
			return ($resource instanceof IResourceObject) ? $resource : new ResourceObject($resource);
		}, $resources);

		return new self($resources);
	}

	/**
	 * @param array $resources
	 */
	public function __construct(array $resources = [])
	{
		$this->addMany($resources);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator() : \ArrayIterator
	{
		return new \ArrayIterator($this->stack);
	}

	/**
	 * {@inheritdoc}
	 */
	public function has(IResourceIdentifier $identifier) : bool
	{
		/** @var IResourceObject $resource */
		foreach ($this as $resource) {
			if ($identifier->isSame($resource->getIdentifier())) {
				return true;
			}
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get(IResourceIdentifier $identifier) : IResourceObject
	{
		/** @var IResourceObject $resource */
		foreach ($this as $resource) {

			if ($identifier->isSame($resource->getIdentifier())) {
				return $resource;
			}
		}

		throw new Exceptions\RuntimeException('No matching resource in collection: ' . $identifier->toString());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAll() : array
	{
		return $this->stack;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIdentifiers() : IResourceIdentifierCollection
	{
		$collection = new ResourceIdentifierCollection;

		/** @var IResourceObject $resource */
		foreach ($this as $resource) {
			$collection->add($resource->getIdentifier());
		}

		return $collection;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isEmpty() : bool
	{
		return empty($this->stack);
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() : int
	{
		return count($this->stack);
	}

	/**
	 * @param IResourceObject $resource
	 *
	 * @return void
	 */
	public function add(IResourceObject $resource) : void
	{
		if (!$this->has($resource->getIdentifier())) {
			$this->stack[] = $resource;
		}
	}

	/**
	 * @param array $resources
	 *
	 * @return void
	 */
	public function addMany(array $resources) : void
	{
		foreach ($resources as $resource) {
			if (!$resource instanceof IResourceObject) {
				throw new Exceptions\InvalidArgumentException('Expecting only resource objects.');
			}

			$this->add($resource);
		}
	}
}
