<?php
/**
 * ITargetEntitySchemasProvider.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           06.05.18
 */

declare(strict_types = 1);

namespace IPub\JsonAPIClient\DI;

interface ITargetEntitySchemasProvider
{
	/**
	 * Returns associative array of Entity class => Schema class definition
	 *
	 * @return array
	 */
	function getTargetEntitySchemaMappings() : array;
}
