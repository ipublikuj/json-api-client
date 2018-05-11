<?php
/**
 * Relationship.php
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
 * Relationship
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Relationship extends StandardObject implements IRelationship
{
	use TMetaMember;

	/**
	 * {@inheritdoc}
	 */
	public function getData()
	{
		if ($this->isHasMany()) {
			return $this->getIdentifiers();

		} elseif (!$this->isHasOne()) {
			throw new Exceptions\RuntimeException('No data member or data member is not a valid relationship.');
		}

		return $this->hasIdentifier() ? $this->getIdentifier() : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIdentifier() : ?IResourceIdentifier
	{
		if (!$this->isHasOne()) {
			throw new Exceptions\RuntimeException('No data member or data member is not a valid has-one relationship.');
		}

		$data = $this->{self::DATA};

		if (!$data) {
			throw new Exceptions\RuntimeException('No resource identifier - relationship is empty.');
		}

		return new ResourceIdentifier($data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasIdentifier() : bool
	{
		return is_object($this->{self::DATA});
	}

	/**
	 * {@inheritdoc}
	 */
	public function isHasOne() : bool
	{
		if (!$this->has(self::DATA)) {
			return FALSE;
		}

		$data = $this->{self::DATA};

		return is_null($data) || is_object($data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIdentifiers() : IResourceIdentifierCollection
	{
		if (!$this->isHasMany()) {
			throw new Exceptions\RuntimeException('No data member of data member is not a valid has-many relationship.');
		}

		return ResourceIdentifierCollection::create($this->{self::DATA});
	}

	/**
	 * {@inheritdoc}
	 */
	public function isHasMany() : bool
	{
		return is_array($this->{self::DATA});
	}
}
