<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

use Nette\PhpGenerator\PhpNamespace;

class ConsoleGenerator extends AbstractGenerator
{
    /**
     * {@inheritdoc}
     */
    public function projectType(): string
    {
        return 'console';
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
     * {@inheritdoc}
     */
    protected function getDependencies(): array
    {
        // TODO: Implement getDependencies() method.
    }

    /**
     * {@inheritdoc}
     */
    protected function getDevDependencies(): array
    {
        // TODO: Implement getDevDependencies() method.
    }

    /**
     * Get the Console Kernel Class.
     *
     * @return string
     */
    private function getConsoleKernelClass(): string
    {
        $namespace = new PhpNamespace('App\Http');
        $namespace->addUse('Viserio\Component\Foundation\Console\Kernel', 'ConsoleKernel');

        $class = $namespace->addClass('Kernel');
        $class->setFinal()
            ->setExtends('Viserio\Component\Foundation\Console\Kernel');

        return '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL . $namespace->__toString();
    }
}
