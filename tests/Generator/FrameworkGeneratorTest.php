<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Tests\Generator;

use Narrowspark\Project\Configurator\Generator\FrameworkGenerator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @internal
 */
final class FrameworkGeneratorTest extends AbstractHttpGeneratorTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        FrameworkGenerator::$isTest = true;

        $this->generator = new FrameworkGenerator(
            new Filesystem(),
            $this->arrangeConfig()
        );
    }
}
