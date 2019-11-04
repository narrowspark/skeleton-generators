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

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use const DIRECTORY_SEPARATOR;
use function in_array;

/**
 * @internal
 */
abstract class AbstractGeneratorTest extends TestCase
{
    /** @var string */
    protected $path;

    /** @var \Narrowspark\Skeleton\Generator\AbstractGenerator */
    protected $generator;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->path = __DIR__ . DIRECTORY_SEPARATOR . 'project';
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        (new Filesystem())->remove($this->path);
    }

    /**
     * @return array
     */
    protected function arrangeConfig(): array
    {
        return [
            'app-dir' => $this->path . DIRECTORY_SEPARATOR . 'app',
            'public-dir' => $this->path . DIRECTORY_SEPARATOR . 'public',
            'resources-dir' => $this->path . DIRECTORY_SEPARATOR . 'resources',
            'routes-dir' => $this->path . DIRECTORY_SEPARATOR . 'routes',
            'tests-dir' => $this->path . DIRECTORY_SEPARATOR . 'tests',
            'storage-dir' => $this->path . DIRECTORY_SEPARATOR . 'storage',
            'config-dir' => $this->path . DIRECTORY_SEPARATOR . 'config',
        ];
    }

    /**
     * @param array $config
     * @param array $skip
     */
    protected function arrangeAssertDirectoryExists(array $config, array $skip = []): void
    {
        foreach ($config as $key => $dir) {
            if (in_array($key, $skip, true)) {
                continue;
            }

            self::assertDirectoryExists($dir);
        }
    }
}
