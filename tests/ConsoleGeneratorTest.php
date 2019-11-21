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

use Narrowspark\Skeleton\Generator\ConsoleGenerator;
use Symfony\Component\Filesystem\Filesystem;
use const DIRECTORY_SEPARATOR;
use function unlink;

/**
 * @internal
 *
 * @small
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

        @unlink(__DIR__ . DIRECTORY_SEPARATOR . 'cerebro.stub');
    }

    public function testProjectType(): void
    {
        self::assertSame('console', $this->generator->getSkeletonType());
    }

    public function testGetDependencies(): void
    {
        self::assertSame(
            [
                'viserio/config' => 'dev-master',
                'viserio/foundation' => 'dev-master',
                'viserio/log' => 'dev-master',
                'viserio/exception' => 'dev-master',
            ],
            $this->generator->getDependencies()
        );
    }

    public function testGetDevDependencies(): void
    {
        self::assertSame(
            [
                'vlucas/phpdotenv' => '^3.6.0',
                'phpunit/phpunit' => '^8.4.3',
            ],
            $this->generator->getDevDependencies()
        );
    }

    public function testGenerate(): void
    {
        $this->generator->generate();

        $config = $this->arrangeConfig();

        $this->arrangeAssertDirectoryExists($config, ['resources-dir', 'public-dir', 'routes-dir']);

        self::assertDirectoryExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Console');
        self::assertFileExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'Kernel.php');
        self::assertFileExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'Bootstrap' . DIRECTORY_SEPARATOR . 'LoadConsoleCommand.php');
        self::assertDirectoryExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Provider');
        self::assertDirectoryNotExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Http/Middleware');
        self::assertFileNotExists($config['app-dir'] . DIRECTORY_SEPARATOR . 'Http/Controller/Controller.php');

        self::assertDirectoryNotExists($this->path . DIRECTORY_SEPARATOR . 'resources/lang');
        self::assertDirectoryNotExists($this->path . DIRECTORY_SEPARATOR . 'resources/views');

        self::assertFileExists($config['storage-dir'] . DIRECTORY_SEPARATOR . 'framework/.gitignore');
        self::assertFileExists($config['storage-dir'] . DIRECTORY_SEPARATOR . 'logs/.gitignore');

        self::assertDirectoryNotExists($config['tests-dir'] . DIRECTORY_SEPARATOR . 'Feature');
        self::assertDirectoryExists($config['tests-dir'] . DIRECTORY_SEPARATOR . 'Unit');
        self::assertFileExists($config['tests-dir'] . DIRECTORY_SEPARATOR . 'AbstractTestCase.php');

        $bootstrapFile = $config['config-dir'] . DIRECTORY_SEPARATOR . 'bootstrap.php';

        self::assertFileExists($bootstrapFile);
        self::assertIsArray(require $bootstrapFile);
    }
}
