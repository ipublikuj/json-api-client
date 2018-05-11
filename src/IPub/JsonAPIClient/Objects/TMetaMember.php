<?php
/**
 * TMetaMember.php
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

use Neomerx\JsonApi\Contracts\Document\DocumentInterface;

use IPub\JsonAPIClient\Exceptions;

/**
 * Meta member trait
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
trait TMetaMember
{
	/**
	 * Get the meta member of the document.
	 *
	 * @return StandardObjectInterface
	 *
	 * @throws Exceptions\RuntimeException if the meta member is present and is not an object or null
	 */
	public function getMeta() : StandardObjectInterface
	{
		$meta = $this->hasMeta() ? $this->get(DocumentInterface::KEYWORD_META) : new StandardObject();

		if (!is_null($meta) && !$meta instanceof StandardObjectInterface) {
			throw new Exceptions\RuntimeException('Data member is not an object.');
		}

		return $meta;
	}

	/**
	 * @return bool
	 */
	public function hasMeta() : bool
	{
		return $this->has(DocumentInterface::KEYWORD_META);
	}
}
