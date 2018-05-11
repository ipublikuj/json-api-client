<?php
/**
 * IMutableError.php
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

use Neomerx\JsonApi\Contracts\Document\DocumentInterface;
use Neomerx\JsonApi\Contracts\Document\ErrorInterface;
use Neomerx\JsonApi\Contracts\Document\LinkInterface;

/**
 * Error response interface
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IMutableError extends ErrorInterface
{
	/**
	 * Keywords for array exchanging
	 */
	public const ID = DocumentInterface::KEYWORD_ERRORS_ID;
	public const STATUS = DocumentInterface::KEYWORD_ERRORS_STATUS;
	public const CODE = DocumentInterface::KEYWORD_ERRORS_CODE;
	public const TITLE = DocumentInterface::KEYWORD_ERRORS_TITLE;
	public const DETAIL = DocumentInterface::KEYWORD_ERRORS_DETAIL;
	public const META = DocumentInterface::KEYWORD_ERRORS_META;
	public const SOURCE = DocumentInterface::KEYWORD_ERRORS_SOURCE;
	public const LINKS = DocumentInterface::KEYWORD_ERRORS_LINKS;
	public const LINKS_ABOUT = DocumentInterface::KEYWORD_ERRORS_ABOUT;

	/**
	 * Set the error id
	 *
	 * @param string|int|NULL $id
	 *
	 * @return void
	 */
	public function setId($id) : void;

	/**
	 * Does the error have an id?
	 *
	 * @return bool
	 */
	public function hasId() : bool;

	/**
	 * Set links on the error, removing any existing links
	 *
	 * @param array|NULL $links
	 *
	 * @return void
	 */
	public function setLinks(array $links = NULL) : void;

	/**
	 * Add links to the error (merging with existing links)
	 *
	 * @param array|NULL $links
	 *
	 * @return void
	 */
	public function addLinks(array $links) : void;

	/**
	 * Add a link to the error
	 *
	 * @param string $key
	 * @param LinkInterface $link
	 *
	 * @return void
	 */
	public function addLink(string $key, LinkInterface $link) : void;

	/**
	 * Set the 'about' link on the error
	 *
	 * @param LinkInterface $link
	 *
	 * @return void
	 */
	public function setAboutLink(LinkInterface $link) : void;

	/**
	 * Set the error status
	 *
	 * @param string|int|NULL $status
	 *
	 * @return void
	 */
	public function setStatus($status) : void;

	/**
	 * Does the error have a status?
	 *
	 * @return bool
	 */
	public function hasStatus() : bool;

	/**
	 * Set the error code
	 *
	 * @param string|int|NULL $code
	 *
	 * @return void
	 */
	public function setCode($code) : void;

	/**
	 * Does the error have a code?
	 *
	 * @return bool
	 */
	public function hasCode() : bool;

	/**
	 * Set the error title
	 *
	 * @param string|NULL $title
	 *
	 * @return void
	 */
	public function setTitle(?string $title) : void;

	/**
	 * Does the error have a title?
	 *
	 * @return bool
	 */
	public function hasTitle() : bool;

	/**
	 * Set the error detail
	 *
	 * @param string|NULL $detail
	 *
	 * @return void
	 */
	public function setDetail(?string $detail) : void;

	/**
	 * Does the error have detail?
	 *
	 * @return bool
	 */
	public function hasDetail() : bool;

	/**
	 * Set the error source, removing any existing source
	 *
	 * @param array|NULL $source
	 *
	 * @return void
	 */
	public function setSource(array $source = NULL) : void;

	/**
	 * Set the error source pointer
	 *
	 * @param string|NULL $pointer
	 *
	 * @return void
	 */
	public function setSourcePointer(?string $pointer) : void;

	/**
	 * Get the error source pointer
	 *
	 * @return string|NULL
	 */
	public function getSourcePointer() : ?string;

	/**
	 * Does the error have a source pointer?
	 *
	 * @return bool
	 */
	public function hasSourcePointer() : bool;

	/**
	 * Set the error source parameter
	 *
	 * @param string|NULL $parameter
	 *
	 * @return void
	 */
	public function setSourceParameter(?string $parameter) : void;

	/**
	 * Get the error source parameter
	 *
	 * @return string|NULL
	 */
	public function getSourceParameter() : ?string;

	/**
	 * Does the error have a source parameter?
	 *
	 * @return bool
	 */
	public function hasSourceParameter() : bool;

	/**
	 * Set the error meta, removing any existing meta
	 *
	 * @param array $meta
	 *
	 * @return void
	 */
	public function setMeta(array $meta) : void;

	/**
	 * Add meta to any existing error meta
	 *
	 * @param array $meta
	 *
	 * @return void
	 */
	public function addMeta(array $meta) : void;

	/**
	 * Merge the provided error into this error
	 *
	 * @param ErrorInterface $error
	 *
	 * @return void
	 */
	public function merge(ErrorInterface $error) : void;

	/**
	 * Merge an array representation of an error into this error
	 *
	 * @param array $input
	 *
	 * @return void
	 */
	public function exchangeArray(array $input) : void;
}
