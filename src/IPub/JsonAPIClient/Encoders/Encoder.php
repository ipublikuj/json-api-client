<?php
/**
 * Encoder.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Encoders
 * @since          1.0.0
 *
 * @date           05.05.18
 */

declare(strict_types = 1);

namespace IPub\JsonAPIClient\Encoders;

use Neomerx\JsonApi\Encoder\Encoder as BaseEncoder;
use Neomerx\JsonApi\Encoder\Serialize\ArraySerializerTrait;

/**
 * Encoder
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Encoders
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Encoder extends BaseEncoder implements ISerializer
{
	use ArraySerializerTrait;
}
