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

namespace Narrowspark\Skeleton\Generator;

use const DIRECTORY_SEPARATOR;
use function array_merge;
use function file_get_contents;

class ConsoleGenerator extends AbstractGenerator
{
    /**
     * {@inheritdoc}
     */
    public function getSkeletonType(): string
    {
        return 'console';
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            'viserio/config' => 'dev-master',
            'viserio/foundation' => 'dev-master',
            'viserio/log' => 'dev-master',
            'viserio/exception' => 'dev-master',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDevDependencies(): array
    {
        return [
            'vlucas/phpdotenv' => '^3.6.0',
            'phpunit/phpunit' => '^8.4.3',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function generate(): void
    {
        parent::generate();

        if (! self::$isTest) {
            $target = 'cerebro';

            $this->filesystem->copy(
                $this->resourcePath . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'cerebro.stub',
                $target
            );
            $this->filesystem->chmod($target, 0755);
        }
    }

    /**
     * Returns all directories that should be generated.
     *
     * @return string[]
     */
    protected function getDirectories(): array
    {
        return array_merge(
            $this->getBasicDirectories(),
            [
                $this->folderPaths['app'] . DIRECTORY_SEPARATOR . 'Console',
                $this->folderPaths['app'] . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'Bootstrap',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFiles(): array
    {
        $consolePath = $this->folderPaths['app'] . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR;

        $files = array_merge(
            $this->getBasicFiles(),
            [
                $this->folderPaths['routes'] . DIRECTORY_SEPARATOR . 'console.php' => '<?php' . "\n" . 'declare(strict_types=1);' . "\n\n" . '/** @var \Viserio\Component\Console\Application $console */' . "\n",
                $consolePath . 'Kernel.php' => $this->getConsoleKernelClass(),
                $consolePath . 'Bootstrap' . DIRECTORY_SEPARATOR . 'LoadConsoleCommand.php' => $this->getLoadConsoleCommand(),
                $this->folderPaths['config'] . DIRECTORY_SEPARATOR . 'bootstrap.php' => $this->getBootstrapContent(),
            ]
        );

        if (! self::$isTest) {
            $files['.env.dist'] = '';
        }

        return $files;
    }

    /**
     * Get the bootstrap file content.
     *
     * @return string
     */
    protected function getBootstrapContent(): string
    {
        return '<?php' . "\n" . 'declare(strict_types=1);' . "\n\n" . 'return [' . "\n" . '    /** > app/bootstrap **/' . "\n" . '    \App\Console\Bootstrap::class => [\'console\'],' . "\n" . '    /** app/bootstrap < **/' . "\n" . '];' . "\n";
    }

    /**
     * Get the console kernel class.
     *
     * @return string
     */
    private function getConsoleKernelClass(): string
    {
        return (string) file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'ConsoleKernel.php.stub');
    }

    /**
     * Get the load console command bootstrap class.
     *
     * @return string
     */
    private function getLoadConsoleCommand(): string
    {
        return (string) file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'LoadConsoleCommand.php.stub');
    }
}
