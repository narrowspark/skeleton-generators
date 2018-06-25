<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Tests\Generator;

/**
 * @internal
 */
abstract class AbstractHttpGeneratorTest extends AbstractGeneratorTest
{
    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        @\unlink(__DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'cerebro');
    }

    public function testGenerate(): void
    {
        $this->generator->generate();

        $config = $this->arrangeConfig();

        $this->arrangeAssertDirectoryExists($config);

        $this->assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Console');
        $this->assertFileExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Console' . \DIRECTORY_SEPARATOR . 'Kernel.php');

        $this->assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Provider');
        $this->assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Http' . \DIRECTORY_SEPARATOR . 'Middleware');
        $this->assertFileExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Http' . \DIRECTORY_SEPARATOR . 'Controller' . \DIRECTORY_SEPARATOR . 'AbstractController.php');

        $this->assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'api.php');
        $this->assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'console.php');
        $this->assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'web.php');

        $this->assertDirectoryExists($config['resources-dir'] . \DIRECTORY_SEPARATOR . 'lang');
        $this->assertDirectoryExists($config['resources-dir'] . \DIRECTORY_SEPARATOR . 'views');

        $this->assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'framework' . \DIRECTORY_SEPARATOR . '.gitignore');
        $this->assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'logs' . \DIRECTORY_SEPARATOR . '.gitignore');

        $this->assertDirectoryExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Feature');
        $this->assertDirectoryExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Unit');
        $this->assertFileExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'AbstractTestCase.php');
    }
}
