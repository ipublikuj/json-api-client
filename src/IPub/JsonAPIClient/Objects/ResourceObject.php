<?php
/**
 * ResourceObject.php
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
use CloudCreativity\Utils\Object\StandardObjectInterface;

use IPub\JsonAPIClient\Exceptions;

/**
 * Resource object
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class ResourceObject extends StandardObject implements IResourceObject
{
	use TIdentifiable;
	use TMetaMember;

	/**
	 * {@inheritdoc}
	 */
	public function getIdentifier() : IResourceIdentifier
	{
		return ResourceIdentifier::create($this->getType(), $this->getId());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAttributes() : StandardObjectInterface
	{
		$attributes = $this->hasAttributes() ? $this->get(self::ATTRIBUTES) : new StandardObject();

		if (!$attributes instanceof StandardObjectInterface) {
			throw new Exceptions\RuntimeException('Attributes member is not an object.');
		}

		return $attributes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasAttributes() : bool
	{
		return $this->has(self::ATTRIBUTES);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelationships() : IRelationships
	{
		$relationships = $this->hasRelationships() ? $this->{self::RELATIONSHIPS} : NULL;

		if (!is_null($relationships) && !is_object($relationships)) {
			throw new Exceptions\RuntimeException('Relationships member is not an object.');
		}

		return new Relationships($relationships);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasRelationships() : bool
	{
		return $this->has(self::RELATIONSHIPS);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelationship(string $key) : IRelationship
	{
		$relationships = $this->getRelationships();

		return $relationships->has($key) ? $relationships->getRelationship($key) : NULL;
	}

}
