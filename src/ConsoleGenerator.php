<?php
declare(strict_types=1);
namespace Narrowspark\Skeleton\Generator;

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
            'viserio/config'     => 'dev-master',
            'viserio/foundation' => 'dev-master',
            'viserio/log'        => 'dev-master',
            'viserio/exception'  => 'dev-master',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDevDependencies(): array
    {
        return [
            'vlucas/phpdotenv' => '^2.5.0',
            'phpunit/phpunit'  => '^7.2.0',
        ];
    }

    /**
     * Returns all directories that should be generated.
     *
     * @return array
     */
    protected function getDirectories(): array
    {
        return \array_merge(
            $this->getBasicDirectories(),
            [
                $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Console',
                $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Console' . \DIRECTORY_SEPARATOR . 'Bootstrap',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFiles(): array
    {
        $consolePath = $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Console' . \DIRECTORY_SEPARATOR;

        $files = \array_merge(
            $this->getBasicFiles(),
            [
                $this->folderPaths['routes'] . \DIRECTORY_SEPARATOR . 'console.php'          => '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL . \PHP_EOL . '/** @var \Viserio\Component\Console\Application $console */' . \PHP_EOL,
                $consolePath . 'Kernel.php'                                                  => $this->getConsoleKernelClass(),
                $consolePath . 'Bootstrap' . \DIRECTORY_SEPARATOR . 'LoadConsoleCommand.php' => $this->getLoadConsoleCommand(),
                $this->folderPaths['config'] . \DIRECTORY_SEPARATOR . 'bootstrap.php'        => $this->getBootstrapContent(),
            ]
        );

        if (! self::$isTest) {
            $files['.env.dist'] = '';
            $files['cerebro']   = \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'cerebro.stub');
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
        return '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL . \PHP_EOL . 'return [' . \PHP_EOL . '    /** > app/bootstrap **/' . \PHP_EOL . '    \App\Console\Bootstrap::class => [\'console\'],' . \PHP_EOL . '    /** app/bootstrap < **/' . \PHP_EOL . '];' . \PHP_EOL;
    }

    /**
     * Get the console kernel class.
     *
     * @return string
     */
    private function getConsoleKernelClass(): string
    {
        return (string) \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'ConsoleKernel.php.stub');
    }

    /**
     * Get the load console command bootstrap class.
     *
     * @return string
     */
    private function getLoadConsoleCommand(): string
    {
        return (string) \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'LoadConsoleCommand.php.stub');
    }
}
