<?php
/**
 * Test: IPub\DataTables\Extension
 * @testCase
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DataTables!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           16.03.18
 */

declare(strict_types = 1);

namespace IPubTests\DataTables;

use Nette;

use Tester;
use Tester\Assert;

use IPub\DataTables;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 * Extension registration tests
 *
 * @package        iPublikuj:DataTables!
 * @subpackage     Tests
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class ExtensionTest extends Tester\TestCase
{
	public function testFunctional() : void
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('dataTables.stateSaver') instanceof DataTables\StateSavers\StateSaver);
		Assert::true($dic->getService('dataTables.grid') instanceof DataTables\Components\Control);
	}

	/**
	 * @return Nette\DI\Container
	 */
	private function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		DataTables\DI\DataTablesExtension::register($config);

		return $config->createContainer();
	}
}

\run(new ExtensionTest());
