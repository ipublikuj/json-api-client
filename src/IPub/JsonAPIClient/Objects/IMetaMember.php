<?php
/**
 * IMetaMember.php
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

use IPub\JsonAPIClient\Exceptions;

/**
 * Meta member interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IMetaMember
{
	/**
	 * Get the meta member of the object
	 *
	 * @return StandardObjectInterface
	 *
	 * @throws Exceptions\RuntimeException if the meta member is present and is not an object
	 */
	public function getMeta() : StandardObjectInterface;

	/**
	 * Does the object have meta?
	 *
	 * @return bool
	 */
	public function hasMeta() : bool;
}
