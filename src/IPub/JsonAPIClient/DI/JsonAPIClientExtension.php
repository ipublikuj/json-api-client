<?php
/**
 * JsonAPIClientExtension.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           05.05.16
 */

declare(strict_types = 1);

namespace IPub\JsonAPIClient\DI;

use Nette;
use Nette\DI;
use Nette\Utils;

use Neomerx\JsonApi;

use IPub\JsonAPIClient\Clients;
use IPub\JsonAPIClient\Schemas;

/**
 * API client extension container
 *
 * @package        iPublikuj:JsonAPIClient!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class JsonAPIClientExtension extends DI\CompilerExtension
{
	/**
	 * @var mixed[]
	 */
	private $defaults = [
		'baseUri' => NULL
	];

	/**
	 * @return void
	 *
	 * @throws Utils\AssertionException
	 */
	public function loadConfiguration() : void
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		// Schemas collector
		$schemaProvider = $builder->addDefinition($this->prefix('schemas'))
			->setType(Schemas\SchemaProvider::class)
			->setFactory(Schemas\SchemaProvider::class);

		// HTTP client
		$builder->addDefinition($this->prefix('client'))
			->setType(Clients\GuzzleClient::class)
			->setArguments(['baseUri' => $config['baseUri']]);

		foreach ($this->compiler->getExtensions() as $extension) {
			if ($extension instanceof ITargetEntitySchemasProvider) {
				$targetMapping = $extension->getTargetEntitySchemaMappings();

				Utils\Validators::assert($targetMapping, 'array');

				$targetMapping = $this->normalizeTargetEntityMappings($targetMapping);

				foreach ($targetMapping as $entity => $schema) {
					if (strpos($schema, '@') === 1) {
						$schema = $builder->getByType(substr($schema, 1));
					}

					$schemaProvider->addSetup('?->addMapping(?, ?)', [$schemaProvider, $entity, $schema]);
				}
			}
		}
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(Nette\Configurator $config, $extensionName = 'jsonApiClient') : void
	{
		$config->onCompile[] = function (Nette\Configurator $config, DI\Compiler $compiler) use ($extensionName) : void {
			$compiler->addExtension($extensionName, new JsonAPIClientExtension);
		};
	}

	/**
	 * @param array $targetMapping
	 *
	 * @return mixed[]
	 *
	 * @throws Utils\AssertionException
	 */
	private function normalizeTargetEntityMappings(array $targetMapping) : array
	{
		$normalized = [];

		foreach ($targetMapping as $entity => $schema) {
			$entity = ltrim($entity, '\\');

			Utils\Validators::assert($schema, 'string|callable');

			if (!is_callable($schema)) {
				$schema = ltrim($schema, '\\');
			}

			$normalized[$entity] = $schema;
		}

		return $normalized;
	}
}
