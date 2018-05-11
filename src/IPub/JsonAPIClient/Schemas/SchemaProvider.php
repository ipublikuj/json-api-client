<?php
/**
 * SchemaProvider.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Schemas
 * @since          1.0.0
 *
 * @date           05.05.18
 */

declare(strict_types = 1);

namespace IPub\JsonAPIClient\Schemas;

use Nette;

/**
 * Schemas provider
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Schemas
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class SchemaProvider
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var array
	 */
	private $mapping = [];

	/**
	 * @param string $entity
	 * @param string|callable $schema
	 *
	 * @return void
	 */
	public function addMapping(string $entity, $schema) : void
	{
		$this->mapping[$entity] = $schema;
	}

	/**
	 * @return array
	 */
	public function getMapping() : array
	{
		return $this->mapping;
	}
}
