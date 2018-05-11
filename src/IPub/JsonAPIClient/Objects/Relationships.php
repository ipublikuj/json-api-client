<?php
/**
 * Relationships.php
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

use CloudCreativity\Utils\Object\StandardObject;

use IPub\JsonAPIClient\Exceptions;

/**
 * Relationships
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Relationships extends StandardObject implements IRelationships
{
	/**
	 * {@inheritdoc}
	 */
	public function getAll() : \Traversable
	{
		foreach ($this->keys() as $key) {
			yield $key => $this->getRelationship($key);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelationship(string $key) : IRelationship
	{
		if (!$this->has($key)) {
			throw new Exceptions\RuntimeException(sprintf('Relationship member "%s" is not present.', $key));
		}

		$value = $this->{$key};

		if (!is_object($value)) {
			throw new Exceptions\RuntimeException(sprintf('Relationship member "%s" is not an object.', $key));
		}

		return new Relationship($value);
	}
}
