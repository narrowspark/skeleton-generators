<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Tests;

use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use Narrowspark\Project\Configurator\Util;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function getPluginName(): void
    {
        $this->assertSame('narrowspark/project-configurator', Util::PLUGIN_NAME);
    }

    public function testGetComposerJsonFileAndManipulator(): void
    {
        [$json, $manipulator] = Util::getComposerJsonFileAndManipulator();

        $this->assertInstanceOf(JsonFile::class, $json);
        $this->assertInstanceOf(JsonManipulator::class, $manipulator);
    }
}