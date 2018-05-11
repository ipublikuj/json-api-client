<?php
/**
 * ResourceIdentifier.php
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
 * Resource identifier object
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class ResourceIdentifier extends StandardObject implements IResourceIdentifier
{
	use TIdentifiable;
	use TMetaMember;

	/**
	 * @param string $type
	 * @param string $id
	 *
	 * @return ResourceIdentifier
	 */
	public static function create(string $type, string $id) : IResourceIdentifier
	{
		$identifier = new self();

		$identifier->set(self::TYPE, $type)
			->set(self::ID, $id);

		return $identifier;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isType($typeOrTypes) : bool
	{
		return in_array($this->get(self::TYPE), (array) $typeOrTypes, TRUE);
	}

	/**
	 * {@inheritdoc}
	 */
	public function mapType(array $map)
	{
		$type = $this->getType();

		if (array_key_exists($type, $map)) {
			return $map[$type];
		}

		throw new Exceptions\RuntimeException(sprintf('Type "%s" is not in the supplied map.', $type));
	}

	/**
	 * {@inheritdoc}
	 */
	public function isComplete() : bool
	{
		return $this->hasType() && $this->hasId();
	}

	/**
	 * {@inheritdoc}
	 */
	public function isSame(IResourceIdentifier $identifier) : bool
	{
		return $this->getType() === $identifier->getType() &&
			$this->getId() === $identifier->getId();
	}

	/**
	 * {@inheritdoc}
	 */
	public function toString() : string
	{
		return sprintf('%s:%s', $this->getType(), $this->getId());
	}
}
