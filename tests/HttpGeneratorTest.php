<?php

declare(strict_types=1);

/**
 * This file is part of Narrowspark Framework.
 *
 * (c) Daniel Bannert <d.bannert@anolilab.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Narrowspark\Skeleton\Generator\Tests;

use Narrowspark\Skeleton\Generator\HttpGenerator;
use Symfony\Component\Filesystem\Filesystem;
use const DIRECTORY_SEPARATOR;
use function unlink;

/**
 * @internal
 *
 * @small
 */
final class HttpGeneratorTest extends AbstractGeneratorTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        HttpGenerator::$isTest = true;

        $this->generator = new HttpGenerator(
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

        @unlink(__DIR__ . DIRECTORY_SEPARATOR . 'cerebro.stub');
    }

    public function testProjectType(): void
    {
        self::assertSame('http', $this->generator->getSkeletonType());
    }

    public function testGetDependencies(): void
    {
        self::assertSame(
            [
                'viserio/config' => 'dev-master',
                'viserio/foundation' => 'dev-master',
                'viserio/log' => 'dev-master',
                'viserio/exception' => 'dev-master',
                'cakephp/chronos' => '^1.2.8',
                'narrowspark/http-emitter' => '^1.0.0',
                'viserio/http-foundation' => 'dev-master',
                'viserio/view' => 'dev-master',
            ],
            $this->generator->getDependencies()
        );
    }

    public function testGenerate(): void
    {
        $this->generator->generate();

        $config = $this->arrangeConfig();

        $this->arrangeAssertDirectoryExists($config);

        self::assertDirectoryExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Console');
        self::assertFileExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'Kernel.php');

        self::assertDirectoryExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Provider');
        self::assertDirectoryExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Middleware');
        self::assertFileExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'AbstractController.php');
        self::assertFileExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Bootstrap' . DIRECTORY_SEPARATOR . 'LoadRoutes.php');

        self::assertFileExists($config['routes-dir'] . DIRECTORY_SEPARATOR . 'api.php');
        self::assertFileExists($config['routes-dir'] . DIRECTORY_SEPARATOR . 'web.php');

        self::assertDirectoryExists($config['resources-dir'] . DIRECTORY_SEPARATOR . 'views');

        self::assertDirectoryExists($config['storage-dir']);
        self::assertFileExists($config['storage-dir'] . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . '.gitignore');
        self::assertFileExists($config['storage-dir'] . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . '.gitignore');

        self::assertDirectoryExists($config['tests-dir']);
        self::assertDirectoryExists($config['tests-dir'] . DIRECTORY_SEPARATOR . 'Feature');
        self::assertDirectoryExists($config['tests-dir'] . DIRECTORY_SEPARATOR . 'Unit');
        self::assertFileExists($config['tests-dir'] . DIRECTORY_SEPARATOR . 'AbstractTestCase.php');

        $bootstrapFile = $config['config-dir'] . DIRECTORY_SEPARATOR . 'bootstrap.php';

        self::assertFileExists($bootstrapFile);
        self::assertIsArray(require $bootstrapFile);
    }
}
