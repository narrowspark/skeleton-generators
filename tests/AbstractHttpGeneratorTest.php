<?php
declare(strict_types=1);
namespace Narrowspark\Skeleton\Generator\Tests;

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

        @\unlink(__DIR__ . \DIRECTORY_SEPARATOR . 'cerebro');
    }

    public function testGenerate(): void
    {
        $this->generator->generate();

        $config = $this->arrangeConfig();

        $this->arrangeAssertDirectoryExists($config);

        static::assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Console');
        static::assertFileExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Console' . \DIRECTORY_SEPARATOR . 'Kernel.php');

        static::assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Provider');
        static::assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Http' . \DIRECTORY_SEPARATOR . 'Middleware');
        static::assertFileExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Http' . \DIRECTORY_SEPARATOR . 'Controller' . \DIRECTORY_SEPARATOR . 'AbstractController.php');

        static::assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'api.php');
        static::assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'console.php');
        static::assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'web.php');

        static::assertDirectoryExists($config['resources-dir'] . \DIRECTORY_SEPARATOR . 'lang');
        static::assertDirectoryExists($config['resources-dir'] . \DIRECTORY_SEPARATOR . 'views');

        static::assertDirectoryExists($config['storage-dir']);
        static::assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'framework' . \DIRECTORY_SEPARATOR . '.gitignore');
        static::assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'logs' . \DIRECTORY_SEPARATOR . '.gitignore');

        static::assertDirectoryExists($config['tests-dir']);
        static::assertDirectoryExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Feature');
        static::assertDirectoryExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Unit');
        static::assertFileExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'AbstractTestCase.php');
    }
}
