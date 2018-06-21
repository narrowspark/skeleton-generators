<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

final class ConsoleGenerator extends AbstractGenerator
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
    public function getDirectories(): array
    {
        // TODO: Implement getDirectories() method.
    }
}