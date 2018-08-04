<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

final class FrameworkGenerator extends AbstractHttpGenerator
{
    /**
     * Returns the project type of the class.
     *
     * @return string
     */
    public function getSkeletonType(): string
    {
        return 'framework';
    }
}
