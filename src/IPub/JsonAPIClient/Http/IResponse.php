<?php
/**
 * IResponse.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Http
 * @since          1.0.0
 *
 * @date           05.05.18
 */

declare(strict_types = 1);

namespace IPub\JsonAPIClient\Http;

use Psr\Http\Message\ResponseInterface as PsrResponse;

use IPub\JsonAPIClient\Objects;

/**
 * Client response interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Http
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IResponse
{
	/**
	 * @return PsrResponse
	 */
	public function getPsrResponse() : PsrResponse;

	/**
	 * The parsed response document, if the response has body content
	 *
	 * @return Objects\IDocument|NULL
	 */
	public function getDocument() : ?Objects\IDocument;
}
