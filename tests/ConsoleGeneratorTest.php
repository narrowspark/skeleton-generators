<?php
declare(strict_types=1);
namespace Narrowspark\Skeleton\Generator\Tests;

use Narrowspark\Skeleton\Generator\ConsoleGenerator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @internal
 */
final class ConsoleGeneratorTest extends AbstractGeneratorTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        ConsoleGenerator::$isTest = true;

        $this->generator = new ConsoleGenerator(
            new Filesystem(),
            $this->arrangeConfig()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        @\unlink(__DIR__ . \DIRECTORY_SEPARATOR . 'cerebro');
    }

    public function testProjectType(): void
    {
        static::assertSame('console', $this->generator->getSkeletonType());
    }

    public function testGetDependencies(): void
    {
        static::assertSame([], $this->generator->getDependencies());
    }

    public function testGetDevDependencies(): void
    {
        static::assertSame(
            [
                'vlucas/phpdotenv' => '^2.3.0',
                'phpunit/phpunit'  => '^7.2.0',
            ],
            $this->generator->getDevDependencies()
        );
    }

    public function testGenerate(): void
    {
        $this->generator->generate();

        $config  = $this->arrangeConfig();

        $this->arrangeAssertDirectoryExists($config, ['resources-dir', 'public-dir']);

        static::assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Console');
        static::assertFileExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Console' . \DIRECTORY_SEPARATOR . 'Kernel.php');
        static::assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Provider');
        static::assertDirectoryNotExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Http/Middleware');
        static::assertFileNotExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Http/Controller/Controller.php');

        static::assertFileNotExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'api.php');
        static::assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'console.php');
        static::assertFileNotExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'web.php');

        static::assertDirectoryNotExists($this->path . \DIRECTORY_SEPARATOR . 'resources/lang');
        static::assertDirectoryNotExists($this->path . \DIRECTORY_SEPARATOR . 'resources/views');

        static::assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'framework/.gitignore');
        static::assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'logs/.gitignore');

        static::assertDirectoryNotExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Feature');
        static::assertDirectoryExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Unit');
        static::assertFileExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'AbstractTestCase.php');
    }
}
