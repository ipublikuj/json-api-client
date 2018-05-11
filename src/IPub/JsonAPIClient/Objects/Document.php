<?php
/**
 * Document.php
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

use Neomerx\JsonApi\Exceptions\ErrorCollection;

use IPub\JsonAPIClient\Exceptions;

/**
 * Response document
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     Objects
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Document extends StandardObject implements IDocument
{
	use TMetaMember;

	/**
	 * {@inheritdoc}
	 */
	public function getData()
	{
		if (!$this->has(self::DATA)) {
			throw new Exceptions\RuntimeException('Data member is not present.');
		}

		$data = $this->get(self::DATA);

		if (is_array($data) || is_null($data)) {
			return $data;
		}

		if (!$data instanceof StandardObjectInterface) {
			throw new Exceptions\RuntimeException('Data member is not an object or null.');
		}

		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getResource() : IResourceObject
	{
		$data = $this->{self::DATA};

		if (!is_object($data)) {
			throw new Exceptions\RuntimeException('Data member is not an object.');
		}

		return new ResourceObject($data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getResources() : IResourceObjectCollection
	{
		$data = $this->get(self::DATA);

		if (!is_array($data)) {
			throw new Exceptions\RuntimeException('Data member is not an array.');
		}

		return ResourceObjectCollection::create($data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelationship() : IRelationship
	{
		return new Relationship($this->proxy);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIncluded() : IResourceObjectCollection
	{
		if (!$this->has(self::INCLUDED)) {
			return NULL;
		}

		if (!is_array($data = $this->{self::INCLUDED})) {
			throw new Exceptions\RuntimeException('Included member is not an array.');
		}

		return ResourceObjectCollection::create($data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getErrors() : ?ErrorCollection
	{
		if (!$this->has(self::ERRORS)) {
			return NULL;
		}

		if (!is_array($data = $this->{self::ERRORS})) {
			throw new Exceptions\RuntimeException('Errors member is not an array.');
		}

		return Error::createMany($data);
	}
}
