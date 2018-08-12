<?php
declare(strict_types=1);
namespace Narrowspark\Skeleton\Generator\Tests;

use Narrowspark\Skeleton\Generator\MicroGenerator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @internal
 */
final class MicroGeneratorTest extends AbstractHttpGeneratorTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        MicroGenerator::$isTest = true;

        $this->generator = new MicroGenerator(
            new Filesystem(),
            $this->arrangeConfig()
        );
    }
}
