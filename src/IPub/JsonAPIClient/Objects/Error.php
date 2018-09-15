<?php
/**
 * Error.php
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

use Neomerx\JsonApi\Contracts\Document\ErrorInterface;
use Neomerx\JsonApi\Contracts\Document\LinkInterface;
use Neomerx\JsonApi\Document\Link;
use Neomerx\JsonApi\Exceptions\ErrorCollection;

use IPub\JsonAPIClient\Exceptions;

/**
 * Class Error
 *
 * @package CloudCreativity\LaravelJsonApi
 */
class Error implements IMutableError
{
	/**
	 * @var int|string|NULL
	 */
	private $id = NULL;

	/**
	 * @var array
	 */
	private $links = [];

	/**
	 * @var string|NULL
	 */
	private $status = NULL;

	/**
	 * @var string|NULL
	 */
	private $code = NULL;

	/**
	 * @var string|NULL
	 */
	private $title = NULL;

	/**
	 * @var string|NULL
	 */
	private $detail = NULL;

	/**
	 * @var array
	 */
	private $source = [];

	/**
	 * @var array|NULL
	 */
	private $meta = NULL;

	/**
	 * @param $error
	 *
	 * @return Error
	 */
	public static function cast($error) : Error
	{
		if ($error instanceof self) {
			return $error;

		} elseif (!$error instanceof ErrorInterface) {
			throw new Exceptions\InvalidArgumentException('Expecting an error object.');
		}

		return new self(
			$error->getId(),
			$error->getLinks(),
			$error->getStatus(),
			$error->getCode(),
			$error->getTitle(),
			$error->getDetail(),
			$error->getSource(),
			$error->getMeta()
		);
	}

	/**
	 * Create an error object from an array
	 *
	 * @param array $input
	 *
	 * @return Error
	 */
	public static function create(array $input = []) : Error
	{
		$error = new self();
		$error->exchangeArray($input);

		return $error;
	}

	/**
	 * Create an error collection from an array of error arrays.
	 *
	 * @param array $input
	 *
	 * @return ErrorCollection
	 */
	public static function createMany(array $input) : ErrorCollection
	{
		$errors = new ErrorCollection;

		foreach ($input as $item) {
			$errors->add(self::create((array) $item));
		}

		return $errors;
	}

	/**
	 * @param int|string|NULL $id
	 * @param array|NULL $links
	 * @param int|string|NULL $status
	 * @param int|string|NULL $code
	 * @param string|NULL $title
	 * @param string|NULL $detail
	 * @param array|NULL $source
	 * @param array|NULL $meta
	 */
	public function __construct(
		$id = NULL,
		array $links = NULL,
		$status = NULL,
		$code = NULL,
		?string $title = NULL,
		?string $detail = NULL,
		array $source = NULL,
		array $meta = NULL
	) {
		$this->setId($id);
		$this->setLinks($links);
		$this->setStatus($status);
		$this->setCode($code);
		$this->setTitle($title);
		$this->setDetail($detail);
		$this->setSource($source);
		$this->setMeta($meta);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setId($id) : void
	{
		if (!is_int($id) && !is_string($id) && !is_null($id)) {
			throw new Exceptions\InvalidArgumentException('Expecting error id to be a string, integer or null.');
		}

		$this->id = $id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasId() : bool
	{
		return $this->id !== NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setLinks(array $links = NULL) : void
	{
		$this->links = [];

		if ($links) {
			$this->addLinks($links);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function addLinks(array $links = NULL) : void
	{
		foreach ((array) $links as $key => $link) {
			if (is_string($link)) {
				$link = new Link($link, NULL, TRUE);
			}

			if (!$link instanceof LinkInterface) {
				throw new Exceptions\InvalidArgumentException('Expecting links to contain link objects.');
			}

			$this->addLink($key, $link);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function addLink(string $key, LinkInterface $link) : void
	{
		$this->links[$key] = $link;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setAboutLink(LinkInterface $link) : void
	{
		$this->addLink(self::LINKS_ABOUT, $link);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLinks() : ?array
	{
		return $this->links !== [] ? $this->links : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setStatus($status) : void
	{
		if (!is_int($status) && !is_string($status) && !is_null($status)) {
			throw new Exceptions\InvalidArgumentException('Expecting error status to be a string, integer or null.');
		}

		$this->status = (string) $status;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getStatus() : ?string
	{
		return $this->hasStatus() ? (string) $this->status : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasStatus() : bool
	{
		return $this->status !== NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCode($code) : void
	{
		if (!is_string($code) && !is_int($code) && !is_null($code)) {
			throw new Exceptions\InvalidArgumentException('Expecting error code to be a string, integer or null.');
		}

		$this->code = (string) $code;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCode() : ?string
	{
		return $this->hasCode() ? $this->code : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasCode() : bool
	{
		return $this->code !== NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTitle(?string $title) : void
	{
		if (!is_string($title) && !is_null($title)) {
			throw new Exceptions\InvalidArgumentException('Expecting error title to be a string or null.');
		}

		$this->title = $title;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTitle() : ?string
	{
		return $this->hasTitle() ? $this->title : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasTitle() : bool
	{
		return $this->title !== NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDetail(?string $detail) : void
	{
		if (!is_string($detail) && !is_null($detail)) {
			throw new Exceptions\InvalidArgumentException('Expecting error detail to be a string or null.');
		}

		$this->detail = $detail;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDetail() : ?string
	{
		return $this->hasDetail() ? $this->detail : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasDetail() : bool
	{
		return $this->detail !== NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setSource(array $source = NULL) : void
	{
		$this->source = (array) $source;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSource() : ?array
	{
		return $this->source !== [] ? $this->source : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setSourcePointer(?string $pointer) : void
	{
		if (!is_string($pointer) && !is_null($pointer)) {
			throw new Exceptions\InvalidArgumentException('Expecting error source pointer to be a string or null');
		}

		if ($pointer === NULL) {
			unset($this->source[self::SOURCE_POINTER]);

		} else {
			$this->source[self::SOURCE_POINTER] = $pointer;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSourcePointer() : ?string
	{
		return $this->hasSourcePointer() ? $this->source[self::SOURCE_POINTER] : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasSourcePointer() : bool
	{
		return isset($this->source[self::SOURCE_POINTER]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setSourceParameter(?string $parameter) : void
	{
		if (!is_string($parameter) && !is_null($parameter)) {
			throw new Exceptions\InvalidArgumentException('Expecting source parameter to be a string or null');
		}

		if ($parameter === NULL) {
			unset($this->source[self::SOURCE_PARAMETER]);

		} else {
			$this->source[self::SOURCE_PARAMETER] = $parameter;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSourceParameter() : ?string
	{
		return $this->hasSourceParameter() ? $this->source[self::SOURCE_PARAMETER] : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasSourceParameter() : bool
	{
		return isset($this->source[self::SOURCE_PARAMETER]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMeta(?array $meta = []) : void
	{
		$this->meta = $meta;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addMeta(array $meta) : void
	{
		$this->meta = $this->meta !== NULL ? array_replace_recursive($this->meta, $meta) : $meta;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMeta() : ?array
	{
		return $this->meta;
	}

	/**
	 * {@inheritdoc}
	 */
	public function merge(ErrorInterface $error) : void
	{
		// Id
		if ($error->getId()) {
			$this->setId($error->getId());
		}

		// Links
		if ($error->getLinks()) {
			$this->addLinks($error->getLinks());
		}

		// Status
		if ($error->getStatus()) {
			$this->setStatus($error->getStatus());
		}

		// Code
		if ($error->getCode()) {
			$this->setCode($error->getCode());
		}

		// Title
		if ($error->getTitle()) {
			$this->setTitle($error->getTitle());
		}

		// Detail
		if ($error->getDetail()) {
			$this->setDetail($error->getDetail());
		}

		// Source
		if ($error->getSource()) {
			$this->setSource($error->getSource());
		}

		// Meta
		if ($error->getMeta()) {
			$this->addMeta($error->getMeta());
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function exchangeArray(array $input) : void
	{
		// Id
		if (array_key_exists(self::ID, $input)) {
			$this->setId($input[self::ID]);
		}

		// Links
		if (array_key_exists(self::LINKS, $input)) {
			$this->addLinks((array) $input[self::LINKS]);
		}

		// About Link
		if (array_key_exists(self::LINKS_ABOUT, $input) && $input[self::LINKS_ABOUT] instanceof LinkInterface) {
			$this->setAboutLink($input[self::LINKS_ABOUT]);
		}

		// Status
		if (array_key_exists(self::STATUS, $input)) {
			$this->setStatus($input[self::STATUS]);
		}

		// Code
		if (array_key_exists(self::CODE, $input)) {
			$this->setCode($input[self::CODE]);
		}

		// Title
		if (array_key_exists(self::TITLE, $input)) {
			$this->setTitle($input[self::TITLE]);
		}

		// Detail
		if (array_key_exists(self::DETAIL, $input)) {
			$this->setDetail($input[self::DETAIL]);
		}

		// Source
		if (array_key_exists(self::SOURCE, $input)) {
			$this->setSource((array) $input[self::SOURCE]);
		}

		// Source Pointer
		if (array_key_exists(self::SOURCE_POINTER, $input)) {
			$this->setSourcePointer($input[self::SOURCE_POINTER]);
		}

		// Source Parameter
		if (array_key_exists(self::SOURCE_PARAMETER, $input)) {
			$this->setSourceParameter($input[self::SOURCE_PARAMETER]);
		}

		// Meta
		if (array_key_exists(self::META, $input)) {
			$this->addMeta((array) $input[self::META]);
		}
	}
}
