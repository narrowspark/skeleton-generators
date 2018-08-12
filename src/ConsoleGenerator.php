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
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getDevDependencies(): array
    {
        return [
            'vlucas/phpdotenv' => '^2.3.0',
            'phpunit/phpunit' => '^7.2.0',
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
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFiles(): array
    {
        return \array_merge(
            $this->getBasicFiles(),
            [
                $this->folderPaths['app'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env.dist'         => '',
                $this->folderPaths['routes'] . \DIRECTORY_SEPARATOR . 'console.php'                                => '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL,
                $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Console' . \DIRECTORY_SEPARATOR . 'Kernel.php' => $this->getConsoleKernelClass(),
                'cerebro'                                                                                          => \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'cerebro.stub'),
            ]
        );
    }

    /**
     * Get the Console Kernel Class.
     *
     * @return string
     */
    private function getConsoleKernelClass(): string
    {
        return (string) \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'ConsoleKernel.php.stub');
    }
}
