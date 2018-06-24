<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

final class MicroGenerator extends AbstractHttpGenerator
{
    /**
     * Returns the project type of the class.
     *
     * @return string
     */
    public function projectType(): string
    {
        return 'micro';
    }
}
