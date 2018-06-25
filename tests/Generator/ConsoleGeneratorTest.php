<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Tests\Generator;

use Narrowspark\Project\Configurator\Generator\ConsoleGenerator;
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

        @\unlink(__DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'cerebro');
    }

    public function testProjectType(): void
    {
        $this->assertSame('console', $this->generator->projectType());
    }

    public function testGenerate(): void
    {
        $this->generator->generate();

        $config  = $this->arrangeConfig();

        $this->arrangeAssertDirectoryExists($config, ['resources-dir', 'public-dir']);

        $this->assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Console');
        $this->assertFileExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Console' . \DIRECTORY_SEPARATOR . 'Kernel.php');
        $this->assertDirectoryExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Provider');
        $this->assertDirectoryNotExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Http/Middleware');
        $this->assertFileNotExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Http/Controller/Controller.php');

        $this->assertFileNotExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'api.php');
        $this->assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'console.php');
        $this->assertFileNotExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'web.php');

        $this->assertDirectoryNotExists($this->path . \DIRECTORY_SEPARATOR . 'resources/lang');
        $this->assertDirectoryNotExists($this->path . \DIRECTORY_SEPARATOR . 'resources/views');

        $this->assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'framework/.gitignore');
        $this->assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'logs/.gitignore');

        $this->assertDirectoryNotExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Feature');
        $this->assertDirectoryExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Unit');
        $this->assertFileExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'AbstractTestCase.php');
    }
}
