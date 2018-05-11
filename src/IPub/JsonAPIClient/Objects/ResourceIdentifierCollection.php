<?php
/**
 * ResourceIdentifierCollection.php
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
 * Resource identifier object
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class ResourceIdentifierCollection implements IResourceIdentifierCollection
{
	/**
	 * @var array
	 */
	private $stack = [];

	/**
	 * @param array $identifiers
	 */
	public function __construct(array $identifiers = [])
	{
		$this->addMany($identifiers);
	}

	/**
	 * @param IResourceIdentifier $identifier
	 *
	 * @return void
	 */
	public function add(IResourceIdentifier $identifier) : void
	{
		if (!$this->has($identifier)) {
			$this->stack[] = $identifier;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function has(IResourceIdentifier $identifier) : bool
	{
		return in_array($identifier, $this->stack);
	}

	/**
	 * @param IResourceIdentifier[] $identifiers
	 *
	 * @return void
	 */
	public function addMany(array $identifiers) : void
	{
		foreach ($identifiers as $identifier) {

			if (!$identifier instanceof IResourceIdentifier) {
				throw new Exceptions\InvalidArgumentException('Expecting only identifier objects.');
			}

			$this->add($identifier);
		}
	}

	/**
	 * @param array $identifiers
	 *
	 * @return void
	 */
	public function setAll(array $identifiers) : void
	{
		$this->clear();
		$this->addMany($identifiers);
	}

	/**
	 * @return void
	 */
	public function clear() : void
	{
		$this->stack = [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator() : \ArrayIterator
	{
		return new \ArrayIterator($this->getAll());
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() : int
	{
		return count($this->stack);
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
	public function isComplete() : bool
	{
		/** @var IResourceIdentifier $identifier */
		foreach ($this as $identifier) {
			if (!$identifier->isComplete()) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isOnly($typeOrTypes) : bool
	{
		/** @var IResourceIdentifier $identifier */
		foreach ($this as $identifier) {
			if (!$identifier->isType($typeOrTypes)) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function map(?array $typeMap = NULL)
	{
		$ret = [];

		/** @var IResourceIdentifier $identifier */
		foreach ($this as $identifier) {

			$key = is_array($typeMap) ? $identifier->mapType($typeMap) : $identifier->getType();

			if (!isset($ret[$key])) {
				$ret[$key] = [];
			}

			$ret[$key][] = $identifier->getId();
		}

		return $ret;
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
	public function getIds() : array
	{
		$ids = [];

		/** @var IResourceIdentifier $identifier */
		foreach ($this as $identifier) {
			$ids[] = $identifier->getId();
		}

		return $ids;
	}

	/**
	 * @param array $input
	 *
	 * @return ResourceIdentifierCollection
	 */
	public static function create(array $input)
	{
		$collection = new static();

		foreach ($input as $value) {
			$collection->add(new ResourceIdentifier($value));
		}

		return $collection;
	}
}
