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

        @\unlink(__DIR__ . \DIRECTORY_SEPARATOR . 'cerebro.stub');
    }

    public function testGetDependencies(): void
    {
        $this->assertSame(
            [
                'viserio/config'           => 'dev-master',
                'viserio/foundation'       => 'dev-master',
                'cakephp/chronos'          => '^1.0.4',
                'narrowspark/http-emitter' => '^0.7.0',
                'viserio/http-foundation'  => 'dev-master',
                'viserio/view'             => 'dev-master',
            ],
            $this->generator->getDependencies()
        );
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
        $this->assertFileExists($config['app-dir'] . \DIRECTORY_SEPARATOR . 'Http' . \DIRECTORY_SEPARATOR . 'Bootstrap' . \DIRECTORY_SEPARATOR . 'LoadRoutes.php');

        $this->assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'api.php');
        $this->assertFileExists($config['routes-dir'] . \DIRECTORY_SEPARATOR . 'web.php');

        $this->assertDirectoryExists($config['resources-dir'] . \DIRECTORY_SEPARATOR . 'views');

        $this->assertDirectoryExists($config['storage-dir']);
        $this->assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'framework' . \DIRECTORY_SEPARATOR . '.gitignore');
        $this->assertFileExists($config['storage-dir'] . \DIRECTORY_SEPARATOR . 'logs' . \DIRECTORY_SEPARATOR . '.gitignore');

        $this->assertDirectoryExists($config['tests-dir']);
        $this->assertDirectoryExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Feature');
        $this->assertDirectoryExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'Unit');
        $this->assertFileExists($config['tests-dir'] . \DIRECTORY_SEPARATOR . 'AbstractTestCase.php');

        $bootstrapFile = $config['config-dir'] . \DIRECTORY_SEPARATOR . 'bootstrap.php';

        $this->assertFileExists($bootstrapFile);
        $this->assertInternalType('array', require $bootstrapFile);
    }
}
