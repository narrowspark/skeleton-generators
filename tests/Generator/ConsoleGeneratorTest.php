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
     * @var \Narrowspark\Project\Configurator\Generator\ConsoleGenerator
     */
    private $generator;

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

        \unlink(__DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'cerebro');
    }

    public function testGenerate(): void
    {
        $this->generator->generate();

        $config  = $this->arrangeConfig();

        $this->arrangeAssertDirectoryExists($config, ['resources-dir', 'public-dir']);

        $this->assertDirectoryExists($config['app-dir'] . '/Console');
        $this->assertDirectoryExists($config['app-dir'] . '/Provider');
        $this->assertDirectoryNotExists($config['app-dir'] . '/Http/Middleware');
        $this->assertFileNotExists($config['app-dir'] . '/Http/Controller/Controller.php');

        $this->assertFileNotExists($config['routes-dir'] . '/api.php');
        $this->assertFileExists($config['routes-dir'] . '/console.php');
        $this->assertFileNotExists($config['routes-dir'] . '/web.php');

        $this->assertDirectoryNotExists($this->path . '/resources/lang');
        $this->assertDirectoryNotExists($this->path . '/resources/views');

        $this->assertFileExists($config['storage-dir'] . '/framework/.gitignore');
        $this->assertFileExists($config['storage-dir'] . '/logs/.gitignore');

        $this->assertDirectoryNotExists($config['tests-dir'] . '/Feature');
        $this->assertDirectoryExists($config['tests-dir'] . '/Unit');
        $this->assertFileExists($config['tests-dir'] . '/AbstractTestCase.php');
    }
}
