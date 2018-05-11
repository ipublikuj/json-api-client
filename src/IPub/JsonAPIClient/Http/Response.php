<?php
/**
 * Response.php
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
 * Client response
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Http
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Response implements IResponse
{
	/**
	 * @var PsrResponse
	 */
	private $response;

	/**
	 * @var Objects\IDocument|NULL
	 */
	private $document;

	/**
	 * @param PsrResponse $response
	 * @param Objects\IDocument|NULL $document
	 */
	public function __construct(PsrResponse $response, ?Objects\IDocument $document = NULL)
	{
		$this->response = $response;
		$this->document = $document;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPsrResponse() : PsrResponse
	{
		return $this->response;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDocument() : Objects\IDocument
	{
		return $this->document;
	}
}
