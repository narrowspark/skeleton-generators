<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

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
        return [];
    }

    /**
     * Returns all directories that should be generated.
     *
     * @return array
     */
    protected function getDirectories(): array
    {
        return [
            $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Console',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getFiles(): array
    {
        return [
            $this->folderPaths['routes'] . \DIRECTORY_SEPARATOR . 'console.php'                                => '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL,
            $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Console' . \DIRECTORY_SEPARATOR . 'Kernel.php' => $this->getConsoleKernelClass(),
            'cerebro'                                                                                          => \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'cerebro.template'),
        ];
    }

    /**
     * Get the Console Kernel Class.
     *
     * @return string
     */
    private function getConsoleKernelClass(): string
    {
        return <<<'PHP'
<?php
declare(strict_types=1);
namespace App\Console;

use Viserio\Component\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
}

PHP;
    }
}
